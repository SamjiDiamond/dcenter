<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table= 'company';
    protected $fillable = [
        'name',
        'email',
        'address',
        'phoneno',
        'bank_code',
        'bank_account',
        'bank_account_name',
        'Monnify_subAccountCode',
        'status',
        'trial_ends_at',
        'paystack_id',
        'sms_balance'
    ];
}
