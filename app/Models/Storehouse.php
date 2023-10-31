<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Storehouse extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * Get the user that owns the workshop.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
