<?php

namespace App\Http\Controllers;

use App\Models\ClassSet;
use Illuminate\Http\Request;

class ClassSetController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Class ID',
            'Class Name',
            'Description',
            'Days',
            'Time',
            'Action'
        ];


        $items = ClassSet::when($search, function ($query, $search) {
            return $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view(
            'pages.class',
            [
                'headers' => $table_header,
                'items' => $items,
                'search' => $search,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'string|max:10',
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'days' => 'required|string|max:255',
            'time' => 'required|string|max:255'
        ]);

        // Create a new user with hashed password
        ClassSet::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'days' => $request->input('days'),
            'time' => $request->input('time'),
        ]);

        return redirect()->route('class');
    }

    public function update(Request $request)
    {
        $request->validate([
            'code' => 'string|max:10',
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'days' => 'required|string|max:255',
            'time' => 'required|string|max:255'
        ]);

        // Create a new user with hashed password
        $item = ClassSet::findOrFail($request->id);
        $item->update($request->all());
        return redirect()->route('class')->with('success', 'Class Details updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:inactive,active',
        ]);

        $user = ClassSet::findOrFail($request->id);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->route('class')->with('success', 'Class status updated successfully');
    }
}
