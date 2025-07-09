<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\AppointmentRepositoryInterface;
use App\Repositories\AppointmentRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
