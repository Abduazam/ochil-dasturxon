<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Users extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = "bot_users";

    protected $primaryKey = "id";

    protected $fillable = ['chat_id', 'first_name', 'username', 'phone_number', 'balance', 'status'];

    public $timestamps = false;

    public function toActive(): void
    {
        $this->status = $this->status === 1 ? self::INACTIVE : self::ACTIVE;
        $this->save();
    }

    public function organization(): HasOneThrough
    {
        return $this->hasOneThrough(
            Organization::class,
            UserOrganization::class,
            'user_id',
            'id',
            'id',
            'organ_id'
        );
    }

    public function location(): HasOne
    {
        return $this->hasOne(UserLocation::class, 'user_id', 'id');
    }
}
