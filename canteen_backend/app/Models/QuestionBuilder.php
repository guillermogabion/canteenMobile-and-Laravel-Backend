<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBuilder extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_title',
        'question_body',
        'question_type',
        'label1',
        'label2',
        'label3',
        'correct',

    ];

    public function exams()
    {
        return $this->belongsTo(ExamBuilder::class);
    }
}
