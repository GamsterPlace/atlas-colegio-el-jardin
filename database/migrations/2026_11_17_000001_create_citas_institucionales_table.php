<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('citas_institucionales',function(Blueprint $table){
   $table->id();$table->string('nombre_visitante',160);$table->string('telefono',30);$table->string('correo',190)->nullable();
   $table->string('motivo',190);$table->text('observaciones')->nullable();$table->date('fecha');
   $table->time('hora_inicio');$table->time('hora_fin');
   $table->enum('estado',['PENDIENTE','CONFIRMADA','REPROGRAMADA','CANCELADA','ATENDIDA'])->default('PENDIENTE');
   $table->unsignedBigInteger('gestionado_por')->nullable();$table->text('nota_interna')->nullable();$table->timestamps();
   $table->index(['fecha','estado']);$table->foreign('gestionado_por')->references('id')->on('usuarios')->nullOnDelete();
  });
 }
 public function down(): void {Schema::dropIfExists('citas_institucionales');}
};
