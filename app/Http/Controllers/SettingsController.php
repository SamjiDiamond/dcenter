<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSystemSettingsRequest;
use App\Models\Settings;
use App\Services\AuditService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * System settings page (live values used across the app).
     */
    public function system()
    {
        if (AuditService::roleNameFor(auth()->id()) !== 'admin') {
            return redirect()->route('dashboard')->withToast('Only admins can manage system settings.', 'danger');
        }

        return view('settings.system', ['settings' => $this->settings()]);
    }

    /**
     * Persist system settings (e.g. the wallet top-up funding fee).
     */
    public function updateSystem(UpdateSystemSettingsRequest $request)
    {
        if (AuditService::roleNameFor(auth()->id()) !== 'admin') {
            return redirect()->route('dashboard')->withToast('Only admins can manage system settings.', 'danger');
        }

        $settings = $this->settings();
        $settings->update(['funding_fee' => $request->input('funding_fee')]);

        AuditService::log(
            'settings.updated',
            'Wallet top-up funding fee updated to ₦' . number_format($settings->funding_fee, 2),
            'warning'
        );

        return redirect()->route('admin.settings.system')->withToast('System settings updated successfully.');
    }

    /**
     * The singleton settings row (id 1), created on first use if missing.
     */
    private function settings()
    {
        return Settings::find(1) ?? Settings::create(['funding_fee' => 80]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settings $settings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settings $settings)
    {
        //
    }
}
