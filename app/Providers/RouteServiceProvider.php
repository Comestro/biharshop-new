<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // ...existing code...

    public function boot()
    {
        $this->routes(function () {
            // ...existing routes...
            
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        });
    }
}
