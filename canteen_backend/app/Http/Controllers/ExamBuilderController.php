<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ExamBuilder;
use Illuminate\Http\Request;

class ExamBuilderController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Exam ID',
            'Exam Title',
            'Description',
            'Type',
            'Status',
            'Action'
        ];
        $types = [
            'boolean',
            'definition',
            'multiple',
        ];

        $select_classroom = Classroom::get();
        $select_exam = ExamBuilder::get();
        // $available = ClassroomUserClassset::where('user_id', Auth::id())
        //     ->get();
        $items = ExamBuilder::when($search, function ($query, $search) {
            return $query->where('exam_name', 'like', '%' . $search . '%')->orWhere('exam_type', 'like', '%' . $search . '%')->orWhere('exam_', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view(
            'pages.exam',
            [
                'headers' => $table_header,
                // 'headers_student' => $table_header_student,
                'items' => $items,
                'search' => $search,
                'classrooms' => $select_classroom,
                'exams' => $select_exam,
                'types' => $types
                // 'available' => $available
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_title' => 'required',
            'exam_body' => 'required',
            'exam_type' => 'required',
            'classroom_id' => 'required',

        ]);

        // Create a new user with hashed password
        ExamBuilder::create([
            'exam_title' => $request->input('exam_title'),
            'exam_body' => $request->input('exam_body'),
            'exam_type' => $request->input('exam_type'),
            'classroom_id' => $request->input('classroom_id'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('exam');
    }
    public function update(Request $request)
    {
        $request->validate([
            'exam_title' => 'required',
            'exam_body' => 'required',
            'exam_type' => 'required',
            'classroom_id' => 'required',
        ]);

        // Create a new user with hashed password
        $item = ExamBuilder::findOrFail($request->id);
        $item->update($request->all());
        return redirect()->route('exam')->with('success', 'Classroom updated successfully');
    }

    public function delete(Request $request)
    {
        $room = ExamBuilder::findOrFail($request->id);
        $room->delete();

        return redirect()->route('exam')->with('success', 'Classroom item deletred successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:inactive,active',
        ]);

        $user = ExamBuilder::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('exam')->with('success', 'Classroom status updated successfully');
    }
}
