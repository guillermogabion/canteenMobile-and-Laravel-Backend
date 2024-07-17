<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_set_id', 'schedule', 'description', 'user_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'classroom_user_classset')
            ->withPivot('class_set_id')
            ->withTimestamps();
    }

    public function classSets()
    {
        return $this->belongsToMany(ClassSet::class, 'classrooms', 'class_set_id')
            ->withTimestamps();
    }
}
