<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'active',
        'created_by'
    ];

    public function subCity()
    {
        return $this->belongsTo(Sub_city::class, 'city_id');
    }
}