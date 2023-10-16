<?php

namespace App\Models;

use App\Models\Deposit;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRolesAndAbilities;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'email', 'password', 'first_name', 'last_name', 'username', 'phoneno', 'gender', 'referral_id', 'image', 'account_no', 'account_type', 'referral', 'company_id', 'role_id', 'trial_ends_at', 'paystack_id', 'accountno', 'monnify_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'updated_at', 'created_at', 'email_verified_at', 'stripe_id', 'paystack_id', 'trials_ends_at', 'api_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'screen_locked' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    /**
     * Define a self-referential relationship for the referrer.
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }

    /**
     * Define a relationship for users referred by this user.
     */
    public function referredUsers()
    {
        return $this->hasMany(User::class, 'referral_id');
    }

    public function initialDeposit()
    {
        return $this->hasOne(Deposit::class)->orderBy('DepositDate');
    }


    public function userNotifications()
    {
        return $this->notifications()->latest()->take(20)->get();
    }

    public function unreadNotificationsCount()
    {
        return  $this->unreadNotifications()->count();
    }

    public function isScreenLocked()
    {
        return $this->screen_locked;
    }

    public function lockScreen()
    {
        $this->screen_locked = true;
        $this->save();
    }

    public function unlockScreen()
    {
        $this->screen_locked = false;
        $this->save();
    }



}
