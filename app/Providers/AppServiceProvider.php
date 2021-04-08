<?php

namespace App\Providers;

use App\Repositories\Api\InvoiceService;
use App\Repositories\Api\RepositoryInterfaces\InvoiceInterface;
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
        $this->app->bind(InvoiceInterface::class, InvoiceService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
