<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Period extends Model
{
    use HasFactory, SoftDeletes;

    private $months = [
        'ENERO' => '01',
        'FEBRERO' => '02',
        'MARZO' => '03',
        'ABRIL' => '04',
        'MAYO' => '05',
        'JUNIO' => '06',
        'JULIO' => '07',
        'AGOSTO' => '08',
        'SEPTIEMBRE' => '09',
        'OCTUBRE' => '10',
        'NOVIEMBRE' => '11',
        'DICIEMBRE' => '12',
    ];

    protected $fillable = [
        'month',
        'year',
        'status',
        'amount',
        'dwelling_uuid',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($period) {
            // si no se envía el campo 'uuid' se genera uno
            if (!isset($period->uuid)) {
                $period->uuid = Uuid::uuid4()->toString();
            }
        });
    }

    public function dwelling()
    {
        return $this->belongsTo(Dwelling::class);
    }

    public function getMonth()
    {
        return array_search($this->month, $this->months);
    }
}
