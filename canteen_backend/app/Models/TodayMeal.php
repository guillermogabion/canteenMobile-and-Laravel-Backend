<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodayMeal extends Model
{
    use HasFactory;
    protected $fillable = [
        'meal_id',
        'today_available',

    ];

    public function todaymeal()
    {
        return $this->belongsTo(Meals::class, 'meal_id');
    }
}
