<?php

namespace CrixuAMG\Responsable;

use Illuminate\Support\Manager;
use CrixuAMG\Responsable\Responders\BladeResponder;
use CrixuAMG\Responsable\Responders\InertiaResponder;
use CrixuAMG\Responsable\Responders\JsonResponder;
use CrixuAMG\Responsable\Responders\RedirectResponder;

class ResponsableManager extends Manager
{
    public function getDefaultDriver()
    {
        throw new \InvalidArgumentException('No default driver');
    }

    public function createJsonDriver(): JsonResponder
    {
        return new JsonResponder();
    }

    public function createBladeDriver(): BladeResponder
    {
        return new BladeResponder();
    }

    public function createInertiaDriver(): InertiaResponder
    {
        return new InertiaResponder();
    }

    public function createRedirectDriver(): RedirectResponder
    {
        return new RedirectResponder();
    }
}
