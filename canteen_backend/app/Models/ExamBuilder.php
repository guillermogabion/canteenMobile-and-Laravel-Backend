<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_title',
        'exam_body',
        'exam_type',
        'user_id',
        'classroom_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
