<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payments extends Model
{
    use HasFactory;

    const STATUS_NOTSEEN = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_NOTACCEPTED = 2;

    protected $table = "payment_proof";

    protected $primaryKey = "id";

    protected $fillable = ['user_id', 'date', 'date', 'status'];

    public $timestamps = false;

    public function user(): hasOne
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function toNotAccept(): void
    {
        if ($this->status === self::STATUS_NOTSEEN) {
            $this->status = self::STATUS_NOTACCEPTED;
            $this->save();
        }
    }
}
