<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Street extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name'
    ];

    // hidden fields
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($street) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($street->uuid)) {
                $street->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    // relationships
    public function dwellings()
    {
        return $this->hasMany(Dwelling::class, 'street_uuid', 'uuid');
    }
}
