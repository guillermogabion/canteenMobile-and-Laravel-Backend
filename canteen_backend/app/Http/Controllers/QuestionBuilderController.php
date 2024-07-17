<?php

namespace App\Http\Controllers;

use App\Models\QuestionBuilder;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class QuestionBuilderController extends Controller
{
    //

    

    public function store(Request $request)
    {
        $request->validate([
            'question_title' => 'required',
            'question_body' => 'required',
            'question_type' => 'required',
            'correct' => 'required',
            'exam_id' => 'required',

        ]);

        // Create a new user with hashed password
        QuestionBuilder::create([
            'question_title' => $request->input('exam_title'),
            'question_body' => $request->input('exam_body'),
            'question_type' => $request->input('exam_type'),
            'label1' => $request->input('label1'),
            'label2' => $request->input('label2'),
            'label3' => $request->input('label3'),
            'exam_id' => $request->input('exam_id'),
            'correct' => $request->input('correct'),
        ]);

        return redirect()->route('exam');
    }
    public function update(Request $request)
    {
        $request->validate([
            'question_title' => 'required',
            'question_body' => 'required',
            'question_type' => 'required',
            'correct' => 'required',
            'exam_id' => 'required',

        ]);

        // Create a new user with hashed password
        $item = QuestionBuilder::findOrFail($request->id);
        $item->update($request->all());
        return redirect()->route('exam')->with('success', 'Classroom updated successfully');
    }

    public function delete(Request $request)
    {
        $room = QuestionBuilder::findOrFail($request->id);
        $room->delete();

        return redirect()->route('exam')->with('success', 'Classroom item deletred successfully');
    }
}
