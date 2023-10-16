<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deposit extends Model
{
    use HasFactory;
    const BANK_TRANSFER = 'bank transfer', 
          PAYMENT_SYSTEM = 'payment system', 
          ATM = 'atm',
          CARD  = 'card';

    protected $fillable = [
        'user_id',
        'initialDeposit',
        'logmentType',
        'amount',
        'balance',
        'phone',
        'address',
        'reference',
        'depositDate',
        'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterDeposits($query, $customerId, $transactionId, $startDate, $endDate, $lodgementType = null)
    {
        return $query->when($lodgementType, function ($q, $lodgementType) {
                $q->where('lodgementType', 'like', '%' . $lodgementType . '%');
            })
            ->with('user')
            ->where('user_id', $customerId)
            ->where('transaction_id', $transactionId)
            ->whereBetween('depositDate', [$startDate, $endDate]);
    }
}
