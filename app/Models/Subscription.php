<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plans;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $table= 'subscriptions';
    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'paystack_id',
        'paystack_code',
        'paystack_plan',
        'quantity',
        'trial_ends_at',
        'ends_at'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, "plan_id");
    }

    public function getRemainingDaysAttribute()
    {

        if ($this->plan_end_date) {
            $remaining_days = Carbon::now()->diffInDays(Carbon::parse($this->plan_end_date));
        } else {
            $remaining_days = 0;
        }
        return $remaining_days;
    }

    public function getRemainingTrialDaysAttribute()
    {

        if ($this->trial_end_date) {
            $remaining_trial_days = Carbon::now()->diffInDays(Carbon::parse($this->trial_end_date));
        } else {
            $remaining_trial_days = 0;
        }
        return $remaining_trial_days;
    }
}
