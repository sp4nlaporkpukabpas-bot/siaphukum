<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

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
    public function boot()
    {
        View::composer('layouts.partials.sidebar', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // 1. Cek apakah active_role_id user ada isinya di DB
                if ($user->active_role_id) {
                    $categories = \App\Models\Category::whereHas('roles', function($q) use ($user) {
                        $q->where('category_role_permissions.role_id', $user->active_role_id)
                        ->where(function($query) {
                            $query->where('can_view', 1)
                                    ->orWhere('can_download', 1);
                        });
                    })->get();

                    // 2. Kirim data ke view
                    $view->with('global_categories', $categories);
                }
            }
        });
    }
}
