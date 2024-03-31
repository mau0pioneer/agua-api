<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Collector extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'name',
        'type'
    ];

    // atributos ocultos
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static $rules = [
        'name' => 'required|string',
        'type' => 'required|in:individual,contribution_center'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collector) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($collector->uuid)) {
                $collector->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    // relaciones
    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'collector_uuid', 'uuid');
    }
}
