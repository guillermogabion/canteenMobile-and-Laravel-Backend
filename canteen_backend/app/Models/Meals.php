<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'price'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function today()
    {
        return $this->hasMany(TodayMeal::class);
    }
}
