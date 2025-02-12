<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'active',
        'remark',
        'created_by',
        'status'
    ];

    public function countryCode() 
    {
        return $this->belongsTo(CountryCode::class, 'code');
    }
}
