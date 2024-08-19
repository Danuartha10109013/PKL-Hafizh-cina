<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'enhancer',
        'date',
        'time',
        'status',
        'coordinate',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'enhancer');
    }
}
