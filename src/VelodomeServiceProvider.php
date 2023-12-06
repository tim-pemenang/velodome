<?php

namespace Velodome\Velodome;

use Illuminate\Support\ServiceProvider;
use Velodome\Velodome\Console\Commands\GenerateController;
use Velodome\Velodome\Console\Commands\GenerateFillableModel;
use Velodome\Velodome\Console\Commands\GenerateMigrationWithFields;
use Velodome\Velodome\Console\Commands\GenerateRoute;

class VelodomeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'velodome');
        $this->commands([
            GenerateController::class,
            GenerateFillableModel::class,
            GenerateMigrationWithFields::class,
            GenerateRoute::class
        ]);
    }
}
