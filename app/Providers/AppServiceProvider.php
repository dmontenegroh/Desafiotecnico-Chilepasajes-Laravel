<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Domain\Donki\Contracts\DonkiRepositoryInterface;
use App\Domain\Donki\Repositories\DonkiRepository;;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DonkiRepositoryInterface::class, DonkiRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
