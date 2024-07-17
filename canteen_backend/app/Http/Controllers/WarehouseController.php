<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
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

        $suppliers = User::where('role', 'supplier')->get();
        $table_header = [
            'Item No',
            'Item Name',
            'Type',
            'Supplier',
            'Location',
            'Branch',

            'Quantity',
            'Status',
            'Expiry Date',
            'Action'
        ];

        return view('pages.warehouse', ['headers' => $table_header, 'suppliers' => $suppliers, 'items' => $items, 'search' => $search]);
    }

    public function getItems(Request $request)
    {
        $search = $request->input('search');

        $items = Warehouse::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        })->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'expiry' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'price' => 'decimal|min:0.00',
            'item_code' => 'unique:warehouses|required|integer|min:1',
            'batch_no' => 'required|string|min:1',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('warehouse');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:warehouses,id',
            'item_name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'expiry' => 'required|date',
            'price' => 'decimal|min:0.00',
            'quantity' => 'required|integer|min:1',
            'batch_no' => 'required|string|min:1',

        ]);

        $item = Warehouse::findOrFail($request->id);
        $item->update($request->all());

        return redirect()->route('warehouse')->with('success', 'Item updated successfully');
    }

    public function updateWarehouseQuantities(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.itemCode' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($data['items'] as $item) {
            // Find the warehouse item by item code
            $warehouseItem = Warehouse::where('item_code', $item['itemCode'])->first();

            if ($warehouseItem) {
                // Update the quantity in the warehouse
                $warehouseItem->quantity -= $item['quantity'];

                // Update the status based on the quantity
                if ($warehouseItem->quantity <= 0) {
                    $warehouseItem->quantity = 0; // Ensure quantity does not go negative
                    $warehouseItem->status = 'inactive';
                } elseif ($warehouseItem->quantity <= 9 && $warehouseItem->quantity >= 5) {
                    $warehouseItem->status = 'critical';
                }

                $warehouseItem->save();
            }
        }

        return response()->json(['message' => 'Warehouse quantities and statuses updated successfully']);
    }
}
