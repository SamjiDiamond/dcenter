<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Template;
use App\Models\User;

class Email extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'paystack_plan',
        'cost',
        'description'
    ];

   public function template(){
       return $this->belongsTo(Template::class);
   }
   
    public function recipient(){
       return $this->belongsTo(User::class,'recipient_id');
   }
}
