<?php

namespace App\Console\Commands;

use App\Services\TiendaUniformesService;
use Illuminate\Console\Command;

class ExpirarPedidosUniformes extends Command
{
    protected $signature = 'uniformes:expirar-pedidos';
    protected $description = 'Anula los pedidos de uniformes que superaron su reserva de 72 horas.';

    public function handle(TiendaUniformesService $servicio): int
    {
        $cantidad = $servicio->expirar();
        $this->info("Pedidos vencidos anulados: {$cantidad}");
        return self::SUCCESS;
    }
}
