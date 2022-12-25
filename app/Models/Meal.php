<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Meal extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = "meals";

    protected $primaryKey = "id";

    protected $fillable = ['title', 'img', 'price', 'status'];

    public $timestamps = false;

    public function toActive(): void
    {
        $this->status = $this->status === 1 ? self::INACTIVE : self::ACTIVE;
        $this->save();
    }

    public function mealStatus($day_id): HasOne
    {
        return $this->hasOne(DailyMenu::class, 'meal_id', 'id')->where('day_id', $day_id);
    }
}
