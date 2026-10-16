<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConfiguracionSistema extends Model
{
    protected $table = 'configuraciones_sistema';

    public $timestamps = false;

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
        'actualizado_por',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'array',
        ];
    }

    public function actualizado_por(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'actualizado_por', 'id');
    }
}
