<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\VoteRepositoryInterface;
use App\Repositories\VoteRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VoteRepositoryInterface::class, VoteRepository::class);
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
