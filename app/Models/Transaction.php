<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'company_id',
        'reference_id',
        'amount',
        'status',
        'description',
        'date',
        'user_id',
        'ip_address',
        'device_id',
        'code',
        'type',
        'i_wallet',
        'f_wallet',
        'extra'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
