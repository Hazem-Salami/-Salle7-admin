<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TowingOrder extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'type',
        'stage',
        'ticket_id',
        'user_email',
        'towing_email',
        'payment_method',
        'address',
        'user_latitude',
        'user_longitude'
    ];
}
