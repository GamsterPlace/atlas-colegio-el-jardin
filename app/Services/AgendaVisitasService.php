<?php
namespace App\Services;
use App\Models\CitaInstitucional;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class AgendaVisitasService {
 // Jornada institucional; se podrá sustituir por configuración en una siguiente fase.
 public const INICIO='07:00'; public const FIN='13:30';
 public function horarios(string $fecha, ?int $excluir=null): array {
  $dia=CarbonImmutable::parse($fecha);
  if($dia->isWeekend() || $dia->startOfDay()->lessThan(CarbonImmutable::now('America/Guatemala')->startOfDay())) return [];
  $ocupados=CitaInstitucional::query()->whereDate('fecha',$fecha)->whereNotIn('estado',['CANCELADA'])
    ->when($excluir,fn($q)=>$q->where('id','!=',$excluir))->get(['hora_inicio','hora_fin']);
  $slots=[];$inicio=CarbonImmutable::parse($fecha.' '.self::INICIO);$fin=CarbonImmutable::parse($fecha.' '.self::FIN);
  for($t=$inicio;$t->addHour()->lessThanOrEqualTo($fin);$t=$t->addHour()){
   if($t->lessThanOrEqualTo(CarbonImmutable::now('America/Guatemala')))continue;
   $a=$t->format('H:i:s');$b=$t->addHour()->format('H:i:s');
   $traslape=$ocupados->contains(fn($c)=>$c->hora_inicio<$b && $c->hora_fin>$a);
   if(!$traslape)$slots[]=['inicio'=>$t->format('H:i'),'etiqueta'=>$t->format('H:i').' – '.$t->addHour()->format('H:i')];
  }
  return $slots;
 }
 public function reservar(array $datos, ?int $excluir=null): CitaInstitucional {
  // Serializar reservas y reprogramaciones en MySQL para evitar solicitudes simultáneas.
  $mysql=DB::getDriverName()==='mysql';
  $lockName='atlas_visita_'.str_replace('-','',$datos['fecha']);
  if($mysql && (int)(DB::selectOne('SELECT GET_LOCK(?, 10) AS acquired',[$lockName])->acquired??0)!==1){
   throw ValidationException::withMessages(['fecha'=>'La agenda está ocupada. Intente nuevamente.']);
  }
  try {return DB::transaction(function() use($datos,$excluir){
   DB::table('citas_institucionales')->where('fecha',$datos['fecha'])->lockForUpdate()->get();
   $disponibles=collect($this->horarios($datos['fecha'],$excluir))->pluck('inicio');
   if(!$disponibles->contains(substr($datos['hora_inicio'],0,5)))throw ValidationException::withMessages(['hora_inicio'=>'Este horario ya no está disponible. Seleccione otro.']);
   $datos['hora_fin']=CarbonImmutable::parse($datos['fecha'].' '.$datos['hora_inicio'])->addHour()->format('H:i:s');
   if($excluir){$c=CitaInstitucional::findOrFail($excluir);$c->update($datos);return $c;}
   return CitaInstitucional::create($datos);
  },3); } finally {if($mysql) DB::selectOne('SELECT RELEASE_LOCK(?) AS released',[$lockName]);}
 }
}
