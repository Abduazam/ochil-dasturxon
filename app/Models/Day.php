<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Day extends Model
{
    use HasFactory;

    protected $table = "days";

    protected $primaryKey = "id";

    protected $fillable = ['day', 'status'];

    public $timestamps = false;

    public function meals(): HasManyThrough
    {
        return $this->hasManyThrough(
            Meal::class,
            DailyMenu::class,
            'day_id',
            'id',
            'id',
            'meal_id'
        )->where('meals.status', 1);
    }

    public function mealStatus($meal_id)
    {
        $meal = DailyMenu::where([
            ['day_id', $this->id],
            ['meal_id', $meal_id]
        ])->get();
        foreach ($meal as $key => $value) {
            return $value->status;
        }
    }
}
