<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Contribution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'amount',
        'folio',
        'comments',
        'neighbor_uuid',
        'dwelling_uuid',
        'collector_uuid',
        'status'
    ];

    // atributos casteados
    protected $casts = [
        'amount' => 'float',
    ];

    // atributos ocultos
    protected $hidden = [
        'id',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'neighbor_name',
        'collector_name'
    ];

    // rules para validación
    public static $rules = [
        'amount' => 'required|numeric',
        'folio' => 'string|nullable',
        'comments' => 'string|nullable',
        'neighbor_uuid' => 'string|exists:neighbors,uuid',
        'dwelling_uuid' => 'string|exists:dwellings,uuid',
        'collector_uuid' => 'string|exists:collectors,uuid',
        'status' => 'string|in:created,assigned,finalized'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contribution) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($contribution->uuid)) {
                $contribution->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    public function getNeighborNameAttribute()
    {
        $firstname = $this->neighbor()->value('firstname');
        $lastname = $this->neighbor()->value('lastname');

        if(is_null($firstname) && is_null($lastname)) return null;

        return $firstname . ' ' . $lastname;
    }

    public function getCollectorNameAttribute()
    {
        return $this->collector()->value('name');
    }

    public function neighbor()
    {
        return $this->belongsTo(Neighbor::class, 'neighbor_uuid', 'uuid');
    }

    public function dwelling()
    {
        return $this->belongsTo(Dwelling::class, 'dwelling_uuid', 'uuid');
    }

    public function collector()
    {
        return $this->belongsTo(Collector::class, 'collector_uuid', 'uuid');
    }

    // validaciones

    // validar folio (formato: XXXX-XX-XXXXX)
    public static function validateFolio($folio)
    {
        return preg_match('/^[0-9]{4}-[A-Z]{2}-[0-9]{5}$/', $folio);
    }
}
