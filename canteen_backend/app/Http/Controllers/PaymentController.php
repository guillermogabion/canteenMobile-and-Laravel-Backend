<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //

    public function store(Request $request)
    {
        $request->validate([
            'received' => 'required|decimal'
        ]);

        Payment::create([
            'received' => $request->input('received')
        ]);
    }
}
