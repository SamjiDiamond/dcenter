<?php

namespace App\Providers;

use App\Models\BouncerRoleModel;
use Bouncer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the company-scoped Bouncer role model once, globally — the
        // documented way (a single registration) instead of repeating
        // Bouncer::useRoleModel() in every controller/seeder.
        Bouncer::useRoleModel(BouncerRoleModel::class);

        Paginator::useBootstrap();

        // Toast helper: redirect()->route(...)->withToast('Saved!', 'success')
        // (also works on redirect()->back()). Flashes a structured 'toast' key
        // that partials.toasts renders as a top-right, auto-dismissing toast.
        RedirectResponse::macro('withToast', function ($message, $type = 'success') {
            /** @var RedirectResponse $this */
            return $this->with('toast', [
                'message' => $message,
                'type'    => in_array($type, ['success', 'danger', 'warning', 'info'], true) ? $type : 'success',
            ]);
        });
    }
}
