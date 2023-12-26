<?php

namespace CrixuAMG\Responsable\Responders;

use CrixuAMG\Responsable\Services\ForwardsConfiguration;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

abstract class ViewResponder extends AbstractResponder
{
    abstract protected function render();

    protected function renderTemplate()
    {
        $template = $this->template;

        if (!$template) {
            $template = sprintf(
                '%s/%s',
                ForwardsConfiguration::getTemplateRoot() ?? $this->controller->plural(),
                $this->method->studly()
            );
        }

        return $template;
    }
}
