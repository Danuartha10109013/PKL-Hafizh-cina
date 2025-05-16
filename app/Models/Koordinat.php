<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Koordinat extends Model
{
    use HasFactory;

    protected $table = 'koordinat';

    protected $fillable = [
        'latitude',
        'longitude',
    ];
}
