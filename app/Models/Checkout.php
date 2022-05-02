<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [ 'first_name', 'email', 'last_name', 'order_id', 'amount', 'quantity', 'currency', 'status'];

}
