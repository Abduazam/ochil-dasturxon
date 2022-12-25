<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBalance extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = "balance_user";

    protected $primaryKey = "id";

    protected $fillable = ['user_id', 'balance', 'status'];

    public $timestamps = false;
}
