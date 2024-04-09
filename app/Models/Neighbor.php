<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Neighbor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'phone_number',
        'prefix',
        'alias',
        'attitude',
        'comments',
    ];

    // atributos casteados
    protected $casts = [
        'phone_number' => 'string',
        'prefix' => 'string',
        'alias' => 'string',
        'access_code' => 'string',
        'atittude' => 'string'
    ];

    // ocultar campos
    protected $hidden = [
        'id',
        'updated_at',
        'deleted_at'
    ];

    // rules para validación
    public static $rules = [
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'phone_number' => 'required|string|min:10|max:10|unique:neighbors,phone_number|regex:/^[0-9]{10}$/',
        'prefix' => 'string|nullable',
        'alias' => 'string|nullable',
        'access_code' => 'string|nullable',
        'atittude' => 'string|nullable',
        'comments' => 'string|nullable',
        'signature' => 'nullable|boolean'
    ];

    // Boot para que se valide si no se envía el campo 'uuid' se genere uno
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($neighbor) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($neighbor->uuid)) {
                $neighbor->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    // dwelings de DwellingNeighbor
    public function dwellings()
    {
        return $this->belongsToMany(Dwelling::class, 'dwelling_neighbors', 'neighbor_uuid', 'dwelling_uuid', 'uuid', 'uuid');
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'neighbor_uuid', 'uuid');
    }
}
