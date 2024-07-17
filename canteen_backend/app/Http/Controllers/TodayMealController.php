<?php

namespace App\Http\Controllers;

use App\Models\TodayMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TodayMealController extends Controller
{
    //

    public function index(Request $request)
    {
        // $search = $request->input('search');
        $table_header = [
            'Dish',
            'Date',
        ];

        // $items = Order::when($search, function ($query, $search) {
        //     return $query->where('name', 'like', '%' . $search . '%');
        // })
        //     ->orderBy('created_at', 'desc')
        //     ->paginate(10);

        $items = TodayMeal::get();

        if ($request->ajax()) {
            return response()->json([
                'headers' => $table_header,
                'items' => $items,
            ]);
        }
        return view(
            'pages.order',
            [
                'headers' => $table_header,
                'items' => $items,
                // 'search' => $search
            ]
        );
    }

    public function storeToday(Request $request)
    {
        $request->validate([
            'meal_id' => 'required',
            'date' => 'required'
        ]);

        $today = TodayMeal::create([
            'meal_id' => $request->input('meal_id'),
            'date' => $request->input('date'),
        ]);
        return response()->json(['today' => $today], 201);
    }
    public function updateToday(Request $request)
    {
        $request->validate([
            'meal_id' => 'required',
            'date' => 'required'
        ]);

        $today = TodayMeal::findOrFail($request->id);
        $today->update($request->all());
        return response()->json(['today' => $today], 201);
    }

    public function deleteOrder(Request $request)
    {
        $today = TodayMeal::findOrFail($request->id);
        $today->delete();

        return response()->json(['today' => $today], 201);
    }

    public function getOrder(Request $request)
    {
        // $search = $request->input('search');

        $today = TodayMeal::where('date', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json(['today' => $today], 201);
    }
    public function getToday()
    {
        try {
            $todayDate = Carbon::now('Asia/Manila')->toDateString();
            $today = TodayMeal::whereDate('date_available', '=', $todayDate)->with('todaymeal')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($today->isEmpty()) {
                return response()->json(['message' => 'No meals available for today'], 404);
            }

            return response()->json(['today' => $today], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch today\'s meals'], 500);
        }
    }
}
