<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //

    public function index(Request $request)
    {

        $search = $request->input('search');

        $items = Warehouse::when($search, function ($query, $search) {
            return $query->where('item_name', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orWhere('location', 'like', '%' . $search . '%')
                ->orWhere('item_code', 'like', '%' . $search . '%')
                ->orWhere('branch', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.inventory', ['items' => $items]);
    }
    public function cashier(Request $request)
    {

        return view('pages.inventory-cashier.cashier');
    }

    public function searchAvailable(Request $request)
    {
        $search = $request->input('search');

        // Check if search query is empty
        if (empty($search)) {
            return response()->json([]);
        }

        $items = Warehouse::when($search, function ($query, $search) {
            return $query->where('item_code', 'like', '%' . $search . '%');
        })->get();

        return response()->json($items);
    }
}
