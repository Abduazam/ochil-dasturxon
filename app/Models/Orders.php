<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Orders extends Model
{
    use HasFactory;

    protected $table = "orders";

    protected $primaryKey = "id";

    protected $fillable = ['user_id', 'meal_id', 'count', 'date', 'created_date'];

    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function meal(): HasOne
    {
        return $this->hasOne(Meal::class, 'id', 'meal_id');
    }
}
