<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyWallet extends Model
{
    use HasFactory;

    protected $table= 'company_wallet';
    protected $fillable = [
        'company_id',
        'paymentid',
        'amount',
        'description',
        'status',
        'old_wallet',
        'new_wallet',
        'type'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
