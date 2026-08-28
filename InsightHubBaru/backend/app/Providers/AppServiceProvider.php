<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                if (in_array($user->role, ['super-admin', 'admin'])) {
                    $exportRequests = \App\Models\ExportRequest::with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();
                } else {
                    $exportRequests = \App\Models\ExportRequest::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                }
                $view->with('exportRequests', $exportRequests);
            } else {
                $view->with('exportRequests', collect());
            }
        });
    }
}
