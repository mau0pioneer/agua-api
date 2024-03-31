<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Signature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'neighbor_uuid',
        'dwelling_uuid'
    ];

    // atributos ocultos
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // rules para validación
    public static $rules = [
        'neighbor_uuid' => 'string|exists:neighbors,uuid',
        'dwelling_uuid' => 'string|exists:dwellings,uuid'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($signature) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($signature->uuid)) {
                $signature->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    // relaciones
    public function neighbor()
    {
        return $this->belongsTo(Neighbor::class, 'neighbor_uuid', 'uuid');
    }

    public function dwelling()
    {
        return $this->belongsTo(Dwelling::class, 'dwelling_uuid', 'uuid');
    }
}
