<?php

namespace App\Providers;

use App\UseCases\OfferImporter\Contracts\OfferImporterCreatorInterface;
use App\UseCases\OfferImporter\OfferImporterCreator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OfferImporterCreatorInterface::class, OfferImporterCreator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
