<?php

namespace App\Models;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceCharge extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'name',
        'user_id',
        'amount',
        'transaction_id',
        'charge_date'
    ];


    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function calculateServiceCharge($amount)
    {
        return $amount * 0.05;
    }
}
