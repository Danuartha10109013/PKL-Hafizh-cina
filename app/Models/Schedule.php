<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'shift_name',
        'singkatan',
        'deleted_by',
        'deleted_at',
        'deleted_by',
        'deleted_at',

    ];
    
    public function scheduleDays()
    {
        return $this->hasMany(ScheduleDayM::class, 'schedule_id');
    }


    public function users()
    {
        return $this->hasMany(User::class, 'schedule', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function days()
    {
        return $this->hasMany(ScheduleDayM::class, 'schedule_id', 'id');
    }
}
