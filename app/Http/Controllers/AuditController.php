<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;

class AuditController extends Controller
{

    public function index()
    {
        $adminId  = request()->input('admin_id');
        $companyId = request()->input('company_id');
        $date =  request()->input('date');

        if (request()->filled('admin_id') &&  request()->filled('company_id')) {
            $sn = 1;
            $auditTrails =  AuditTrail::with(['admin', 'company'])
                ->where('admin_id', $adminId)
                ->where('company_id', $companyId)
                ->whereDate('created_at', $date)
                ->get();

            return view('report_audittrail', compact('auditTrails', 'sn'));
        }

        return view('report_audittrail');
    }
}
