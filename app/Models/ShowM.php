<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowM extends Model
{
    use HasFactory;
    protected $table = 'show_attendance';

    protected $fillable = [
        'show'
    ];
}
