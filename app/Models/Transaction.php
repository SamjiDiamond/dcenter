<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CompanyWallet;
use App\Models\Company;

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
    
      public function companyWallet()
    {
        return $this->belongsTo(CompanyWallet::class, 'wallet_id');
    }
}
