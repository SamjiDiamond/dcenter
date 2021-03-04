<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigTVModel extends Model
{
    use HasFactory;

    protected $table='config_tv';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function default_price()
    {
        return $this->belongsTo(ConfigDefaultModel::class, 'default_id');
    }
}
