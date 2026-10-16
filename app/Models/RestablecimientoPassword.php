<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RestablecimientoPassword extends Model
{
    protected $table = 'restablecimientos_password';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'token_hash',
        'vence_at',
        'utilizado_at',
    ];

    protected function casts(): array
    {
        return [
            'vence_at' => 'datetime',
            'utilizado_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'id');
    }
}
