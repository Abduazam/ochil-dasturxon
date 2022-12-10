<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class DailyMenu extends Model
{
    use HasFactory;

    public const INACTIVE = 0;
    public const ACTIVE = 1;

    protected $table = 'daily_menu';

    protected $primaryKey = "id";

    protected $fillable = ['day_id', 'meal_id', 'status'];

    public function meals(): BelongsTo
    {
        return $this->belongsTo(Meal::class, 'meal_id');
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class, 'day_id');
    }

    public static function inactivate($day_id, $meal_id): void
    {
        $day_menu = self::where([['day_id', $day_id], ['meal_id', $meal_id]])->get();
        foreach ($day_menu as $key => $value) {
             $value->status = $value->status === 1 ? self::INACTIVE : self::ACTIVE;
             $value->save();
        }
    }


}
