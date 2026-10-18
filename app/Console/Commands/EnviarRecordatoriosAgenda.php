<?php

namespace App\Console\Commands;

use App\Models\Notificacion;
use App\Models\RecordatorioAgenda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EnviarRecordatoriosAgenda extends Command
{
    protected $signature = 'agenda:enviar-recordatorios';
    protected $description = 'Genera notificaciones internas para recordatorios de agenda vencidos';

    public function handle(): int
    {
        $enviados = 0;

        RecordatorioAgenda::query()
            ->whereNull('enviado_at')
            ->where('recordar_at', '<=', now())
            ->whereHas('evento_agenda', fn ($query) => $query->where('estado', 'PROGRAMADO'))
            ->orderBy('id')
            ->select('id')
            ->chunkById(100, function ($registros) use (&$enviados) {
                foreach ($registros as $registro) {
                    DB::transaction(function () use ($registro, &$enviados) {
                        $recordatorio = RecordatorioAgenda::query()
                            ->with('evento_agenda')
                            ->lockForUpdate()
                            ->find($registro->id);

                        if (!$recordatorio || $recordatorio->enviado_at ||
                            $recordatorio->recordar_at->isFuture() ||
                            !$recordatorio->evento_agenda ||
                            $recordatorio->evento_agenda->estado !== 'PROGRAMADO') {
                            return;
                        }

                        $evento = $recordatorio->evento_agenda;
                        Notificacion::create([
                            'usuario_id' => $recordatorio->usuario_id,
                            'titulo' => 'Recordatorio: ' . mb_substr($evento->titulo, 0, 175),
                            'mensaje' => 'Actividad programada para el ' . $evento->inicio->format('d/m/Y H:i') . '. ' . ($evento->ubicacion ? 'Lugar: ' . $evento->ubicacion : ''),
                            'tipo' => 'AGENDA',
                            'enlace' => null,
                        ]);
                        $recordatorio->update(['enviado_at' => now()]);
                        $enviados++;
                    });
                }
            });

        $this->info("Recordatorios notificados: {$enviados}");
        return self::SUCCESS;
    }
}
