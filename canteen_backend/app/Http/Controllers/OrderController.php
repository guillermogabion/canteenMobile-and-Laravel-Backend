<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //

    public function index(Request $request)
    {
        // $search = $request->input('search');
        $table_header = [
            'Customer',
            'Dish',
            'Amount',
            'Type',
            'Location',
            'Contact',
            'Action'
        ];

        // $items = Order::when($search, function ($query, $search) {
        //     return $query->where('name', 'like', '%' . $search . '%');
        // })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        $items = Order::get();

        // if ($request->ajax()) {
        //     return response()->json([
        //         'headers' => $table_header,
        //         'items' => $items,
        //     ]);
        // }
        return view(
            'pages.order',
            [
                'headers' => $table_header,
                'items' => $items,
                // 'search' => $search
            ]
        );
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'meal_id' => 'required',
            'user_id' => 'required',
            'type' => 'required',
            'location' => 'required',
            'contact' => 'required',
            'total' => 'required'
        ]);

        $order = Order::create([
            'meal_id' => $request->input('meal_id'),
            'user_id' => auth()->id(),
            'type' => $request->input('type'),
            'location' => $request->input('location'),
            'contact' => $request->input('contact'),
            'total' => $request->input('total'),
            'deliveryStatus' => $request->input('deliveryStatus'),
        ]);
        return response()->json(['order' => $order], 201);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'meal_id' => 'required',
            'location' => 'required|string|max:255',
            'type' => 'required',
            'total' => 'required',
            'contact' => 'required',


        ]);

        $order = Order::findOrFail($request->id);
        $order->update($request->all());
        return response()->json(['order' => $order], 201);
    }

    public function deleteOrder(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->delete();

        return response()->json(['order' => $order], 201);
    }

    public function getOrder(Request $request)
    {
        $search = $request->input('search');

        $order = Order::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->where('user_id', auth()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json(['order' => $order], 201);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'deliveryStatus' => 'required|string|in:preparing,on_the_way,delivered,cancelled',
        ]);

        $order = Order::findOrFail($request->id);
        $order->status = $request->input('deliveryStatus');
        $order->save();

        return response()->json(['order' => $order], 201);
    }
}
