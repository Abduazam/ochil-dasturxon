<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    protected $table = "organizations";

    protected $primaryKey = "id";

    protected $fillable = ['title', 'status'];

    public $timestamps = false;

    public function toActive(): void
    {
        $this->status = $this->status === 1 ? self::INACTIVE : self::ACTIVE;
        $this->save();
    }
}
