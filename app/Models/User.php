<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property mixed $wallet
 */
class User extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'user_type',
        'is_active',
        'password',
        'blocked',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the wallet record associated with the user.
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(UserWallet::class);
    }

    /**
     * Get the workshop record associated with the user.
     */
    public function workshop(): HasOne
    {
        return $this->hasOne(Workshop::class);
    }

    /**
     * Get the workshop record associated with the user.
     */
    public function storehouse(): HasOne
    {
        return $this->hasOne(Storehouse::class);
    }

    /**
     * Get the towing record associated with the user.
     */
    public function towing(): HasOne
    {
        return $this->hasOne(Towing::class);
    }

    public function userfiles(): hasMany
    {
        return $this->hasMany(UserFile::class);
    }

    public function verifyrequest(): HasOne
    {
        return $this->hasOne(VerifyRequest::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(Suggestion::class);
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(Revenue::class);
    }

    public function feedbacksFrom(): HasMany
    {
        return $this->hasMany(Feedback::class,'user_id','id');
    }

    public function feedbacksTo(): HasMany
    {
        return $this->hasMany(Feedback::class,'evaluator_id','id');
    }
}
