<?php

namespace App\Models;

use App\Models\Deposit;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
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
        'email', 'password', 'first_name', 'last_name', 'username', 'phoneno', 'gender', 'referral_id', 'image', 'account_no', 'account_type', 'referral', 'company_id', 'role_id', 'trial_ends_at', 'paystack_id', 'accountno', 'monnify_id', 'status',
        'email_2fa_enabled', 'deletion_requested_at', 'deletion_scheduled_for', 'uuid'
    ];

    /**
     * Users are addressed by their public UUID in web URLs, never the numeric id.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function (User $user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

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
        'email_2fa_enabled' => 'boolean',
        'deletion_requested_at' => 'datetime',
        'deletion_scheduled_for' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'two_factor_enabled',
        'unread_notifications_count',
    ];

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // Photos uploaded via the API are stored under "user/image/…" and are
            // served by the /api/user/image/{filename} route, not Jetstream's
            // /storage/profile-photos/… URL.
            if (Str::startsWith($this->profile_photo_path, 'user/image/')) {
                return url('/api/user/image/' . basename($this->profile_photo_path));
            }

            return Storage::disk('public')->url($this->profile_photo_path);
        }

        return $this->defaultProfilePhotoUrl();
    }

    public function defaultProfilePhotoUrl()
    {
        $name = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        $name = $name !== '' ? $name : ($this->name ?? 'User');

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getTwoFactorEnabledAttribute()
    {
        return (bool) ($this->email_2fa_enabled || ! is_null($this->two_factor_secret));
    }

    /**
     * A short, readable public reference derived from the uuid (first 8 chars).
     */
    public function getReferenceAttribute()
    {
        return strtoupper(Str::substr((string) $this->uuid, 0, 8));
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotificationsCount();
    }

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


    /**
     * The bell dropdown only lists unread notifications; read ones are
     * available on the full history page (route notification.index).
     */
    public function userNotifications()
    {
        return $this->unreadNotifications()->latest()->take(20)->get();
    }

    public function unreadNotificationsCount()
    {
        // The bell badge caps the display at '99+', so there is no point scanning
        // past the 100 most recent unread rows. COUNT(*) ignores LIMIT directly,
        // so count a LIMIT-ed subquery instead of the whole notifications table.
        $recentUnread = $this->unreadNotifications()->select('id')->limit(100);

        return (int) DB::query()->fromSub($recentUnread, 'recent_unread')->count();
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
