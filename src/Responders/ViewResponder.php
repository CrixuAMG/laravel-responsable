<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Responsable;

abstract class ViewResponder extends AbstractResponder
{
    public function toResponse($request)
    {
        $render = $this->render();

        if ($render instanceof Responsable) {
            $render = $render->toResponse($request);
        }

        return $render;
    }

    abstract protected function render();

    protected function renderTemplate()
    {
        $template = $this->template;

        if (!$template) {
            $action = Str::of(request()->route()->action['controller'])
                ->afterLast('\\');

            $controller = $action->before('Controller@');
            $method = $action->after('@');

            $template = sprintf('%s/%s', $controller->plural(), $method->studly());
        }

        return $template;
    }

    protected function wrapData()
    {
        return $this->qualifiedWrapper() ? [$this->qualifiedWrapper() => $this->data] : $this->data;
    }

    protected function qualifiedWrapper()
    {
        $wrap = $this->wrap;
        if ($wrap === null) {
            $action = Str::of(request()->route()->action['controller'])
                ->afterLast('\\');

            $controller = $action->before('Controller@');

            $wrap = $controller->snake()->plural()->toString();
        }

        return $wrap;
    }
}
