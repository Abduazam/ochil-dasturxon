<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLocation extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = "user_location";

    protected $primaryKey = "id";

    protected $fillable = ['chat_id', 'user_id', 'latitude', 'longitude', 'status'];

    public $timestamps = false;
}
