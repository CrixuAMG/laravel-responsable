<?php

namespace CrixuAMG\Responsable\Responders;

use Inertia\Inertia;

class InertiaResponder extends ViewResponder
{
    public function render()
    {
        return Inertia::render($this->renderTemplate(), $this->wrapData());
    }
}
