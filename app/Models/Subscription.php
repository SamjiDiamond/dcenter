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
        'stripe_id',
        'paystack_reference',
        'stripe_status',
        'stripe_plan',
        'quantity',
        'trial_ends_at',
        'ends_at'
    ];
}
