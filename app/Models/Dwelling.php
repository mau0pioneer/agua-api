<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Dwelling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'coordinates_uuid',
        'street_uuid',
        'street_number',
        'interior_number',
        'access_code',
        'inhabited',
        'type',
        'comments'
    ];

    // cast para que el campo 'inhabited' sea booleano y el campo 'type' sea entero
    protected $casts = [
        'inhabited' => 'boolean',
        'type' => 'integer',
        'comments' => 'string'
    ];

    // ocultar campos
    protected $hidden = [
        'id',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'type_color'
    ];

    public static $typeColors = [
        '4' => '#FDB600',
        '3' => '#1FAFB5',
        '2' => '#FC0E93',
        '1' => '#B188E4'
    ];

    // Boot para que se valide si no se envía el campo 'uuid' se genere uno
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dwelling) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($dwelling->uuid)) {
                $dwelling->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    public function getTypeColorAttribute()
    {
        $grayColor = '#919191';
        //if ($this->inhabited === false) return $grayColor;
        return self::$typeColors[$this->type] ?? $grayColor;
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'dwelling_uuid', 'uuid');
    }

    public function street()
    {
        return $this->belongsTo(Street::class, 'street_uuid', 'uuid');
    }

    public function periods()
    {
        return $this->hasMany(Period::class, 'dwelling_uuid', 'uuid');
    }

    public function pendingPeriods()
    {
        return $this->hasMany(Period::class, 'dwelling_uuid', 'uuid')->where('status', 'pending');
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class, 'dwelling_uuid', 'uuid');
    }

    public function neighbors()
    {
        return $this->belongsToMany(Neighbor::class, 'dwelling_neighbors', 'dwelling_uuid', 'neighbor_uuid', 'uuid', 'uuid')->withPivot('condition');
    }
}
