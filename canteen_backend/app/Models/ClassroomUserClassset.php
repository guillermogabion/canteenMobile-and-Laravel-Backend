<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomUserClassset extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_set_id', 'user_id', 'classroom_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classrooms()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function classSet()
    {
        return $this->belongsTo(ClassSet::class, 'class_set_id');
    }
}
