<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template_id',
        'content',
        'user_id',
    ];

    public function template(){
        return $this->belongsTo(Template::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
