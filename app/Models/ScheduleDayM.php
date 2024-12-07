<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDayM extends Model
{
    use HasFactory;
    protected $table = 'schedule_day';
    protected $fillable = [
        'schedule_id',
        'clock_in',
        'break',
        'clock_out',
        'days',
    ];
}
