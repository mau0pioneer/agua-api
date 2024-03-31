<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DwellingNeighbor extends Model
{
    use HasFactory;

    protected $fillable = [
        'dwelling_uuid',
        'neighbor_uuid',
        'condition' // owner, renter, guest
    ];

    // ocultar campos
    protected $hidden = [
        'id',
        'created_at',
        'updated_at'
    ];

    // rules para validación
    public static $rules = [
        'dwelling_uuid' => 'required|string|exists:dwellings,uuid',
        'neighbor_uuid' => 'required|string|exists:neighbors,uuid',
        'condition' => 'string',
    ];

    // relacion con la tabla dwellings
    public function dwelling()
    {
        return $this->belongsTo(Dwelling::class, 'dwelling_uuid', 'uuid');
    }

    // relacion con la tabla neighbors
    public function neighbor()
    {
        return $this->belongsTo(Neighbor::class, 'neighbor_uuid', 'uuid');
    }
}
