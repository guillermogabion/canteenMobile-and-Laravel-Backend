<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ClassroomUserClassset;
use App\Models\ClassSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClassroomController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Room ID',
            'Class Schedule',
            'Description',
            'Status',
            'Action'
        ];
        $table_header_student = [
            'Room ID',
            'Subject',
            'Days',
            'Time',
            'Status',
            'Action'
        ];

        $select_class = ClassSet::get();
        $available = ClassroomUserClassset::where('user_id', Auth::id())
            ->get();
        $items = Classroom::when($search, function ($query, $search) {
            return $query->where('roomId', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view(
            'pages.classroom',
            [
                'headers' => $table_header,
                'headers_student' => $table_header_student,
                'items' => $items,
                'search' => $search, 'subject' => $select_class,
                'available' => $available
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_set_id' => 'required',
            'schedule' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create a new user with hashed password
        Classroom::create([
            'class_set_id' => $request->input('class_set_id'),
            'schedule' => $request->input('schedule'),
            'description' => $request->input('description'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('room');
    }

    public function update(Request $request)
    {
        $request->validate([
            'schedule' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Create a new user with hashed password
        $item = Classroom::findOrFail($request->id);
        $item->update($request->all());
        return redirect()->route('room')->with('success', 'Classroom updated successfully');
    }
    public function delete(Request $request)
    {
        $room = Classroom::findOrFail($request->id);
        $room->delete();

        return redirect()->route('room')->with('success', 'Classroom item deletred successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:inactive,active',
        ]);

        $user = Classroom::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('room')->with('success', 'Classroom status updated successfully');
    }

    public function show($id)
    {
        $item = Classroom::findOrFail($id);
        $available = ClassroomUserClassset::where('user_id', Auth::id())->get();
        return view('pages.room.show', compact('item', 'available'));
    }
}
