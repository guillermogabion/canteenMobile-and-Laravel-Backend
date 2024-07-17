<?php

namespace App\Http\Controllers;

use App\Models\Meals;
use App\Models\TodayMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealsController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search');
        $table_header = [
            'Name',
            'Description',
            'Price',
            'Action'
        ];
        $todayDate = Carbon::now('Asia/Manila')->toDateString();
        $items = Meals::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $today = TodayMeal::whereDate('date_available', 'like', $todayDate)
            ->orderBy('created_at', 'desc')
            ->get();
        return view(
            'pages.meal',
            [
                'headers' => $table_header,
                'items' => $items,
                'todays' => $today,
                'search' => $search
            ]
        );
    }

    public function storeMeal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required',
            'image' => 'required|file|max:10240|mimes:jpg,jpeg,png'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('meal'), $fileName);

            Meals::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'image' => $fileName
            ]);

            return redirect()->route('meal')->with('success', 'Meal added successfully');
        } else {
            return redirect()->route('meal')->with('error', 'Image file is required.');
        }
    }


    public function update(Request $request)
    {
        $meal = Meals::findOrFail($request->id);
        $meal->update($request->all());
        return redirect()->route('meal')->with('success', 'Meal updated successfully');
    }

    public function deleteMeal(Request $request)
    {
        $meal = Meals::findOrFail($request->id);
        $meal->delete();

        return redirect()->route('meal')->with('success', 'Meal deleted successfully');
    }


    public function getMeal(Request $request)
    {
        $search = $request->input('search');

        $meal = Meals::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json(['meal' => $meal], 201);
    }
}
