<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ImageManipulationRepository;
use App\Repositories\Interfaces\ImageManipulationRepositoryInterface;

class ImageManipulationRepoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ImageManipulationRepositoryInterface::class, ImageManipulationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
