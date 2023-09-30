<?php

namespace CrixuAMG\Responsable;

use Illuminate\Support\ServiceProvider;

class ResponsableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfiguration();
    }

    private function registerConfiguration()
    {
        $this->publishes([
            __DIR__ . '/config/responsable.php' => config_path('responsable.php'),
        ]);
    }
}