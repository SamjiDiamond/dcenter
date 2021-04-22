<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Plan::class, "paystack_plan", "paystack_plan");
    }
}
