<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOrganization extends Model
{
    use HasFactory;

    protected $table = "user_organization";

    protected $primaryKey = "id";

    protected $fillable = ['user_id', 'organ_id', 'status'];

    public $timestamps = false;

    public function users(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function organizations(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organ_id');
    }
}
