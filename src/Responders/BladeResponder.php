<?php

namespace CrixuAMG\Responsable\Responders;

class BladeResponder extends ViewResponder
{
    public function render()
    {
        return view($this->renderTemplate(), $this->wrapData());
    }
}
