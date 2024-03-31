<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessDwelling extends Model
{
    use HasFactory;

    protected $fillable = [
        'dwelling_uuid'
    ];

    public function dwelling()
    {
        return $this->belongsTo(Dwelling::class, 'dwelling_uuid', 'uuid');
    }
}
