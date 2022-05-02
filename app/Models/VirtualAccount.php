<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;
    protected $table = 'virtual_account';

    protected $fillable =[
        'account_name',
        'account_reference',
        'bvn'
    ];
}
