<?php

namespace App\Models;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Audit extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'admin_id',
        'action',
        'type'
    ];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

    public function company(){
        return $this->belongsTo(Company::class,'company_id');
    }
}
