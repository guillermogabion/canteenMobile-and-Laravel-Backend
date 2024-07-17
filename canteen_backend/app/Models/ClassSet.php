<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'days', 'time', 'status'
    ];
    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'classroom_user_classset')
            ->withPivot('user_id')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'classroom_user_classset')
            ->withPivot('classroom_id')
            ->withTimestamps();
    }
}
