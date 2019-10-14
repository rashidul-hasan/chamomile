<?php

namespace Rashidul\Chamomile;

use Illuminate\Support\ServiceProvider;
use Rashidul\Chamomile\Generator\Command\GenerateCommand;

class ChamomileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        // publish configs
        $this->publishes([
            __DIR__ . '/../configs/chamomile.php' => config_path('chamomile'),
        ], 'chamomile');



    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        //configs
        $this->mergeConfigFrom(
            __DIR__ . '/../configs/chamomile.php', 'chamomile'
        );

        // register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCommand::class
            ]);
        }
        /*$this->commands(
            'Rashidul\Chamomile\Generator\Command\GenerateCommand'
        );*/

    }
}
