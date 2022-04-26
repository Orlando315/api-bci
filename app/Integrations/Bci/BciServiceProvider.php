<?php

namespace App\Integrations\Bci;

use Illuminate\Support\ServiceProvider;
use App\Integrations\Bci\Bci;

class BciServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
      $this->app->bind(Bci::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->mergeConfigFrom(
          __DIR__.'/Config/bci.php', 'bci'
      );
    }
}
