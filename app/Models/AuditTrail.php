<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trail';

    protected $fillable = [
        'company_id',
        'admin_id',
        'role',
        'action',
        'type',
        'description',
        'subject_type',
        'subject_id',
        'severity',
        'ip_address',
        'user_agent',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
