<?php

namespace App\Http\Controllers;

use App\Models\ClassroomUserClassset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomUserClasssetController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|string|max:255',

        ]);

        // Create a new user with hashed password
        ClassroomUserClassset::create([
            'classroom_id' => $request->input('classroom_id'),
            'user_id' => Auth::id()
        ]);

        return redirect()->route('class');
    }
}
