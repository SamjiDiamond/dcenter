<?php

namespace App\Models;

use Silber\Bouncer\Database\Role;

class BouncerRoleModel extends Role
{
    protected $fillable = ['name', 'title', 'level', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
