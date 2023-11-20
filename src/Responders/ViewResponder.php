<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

abstract class ViewResponder extends AbstractResponder
{
    private Stringable $controller;

    private Stringable $method;

    public function __construct()
    {
        $action = Str::of(request()->route()->action['controller'])
            ->afterLast('\\');

        $this->controller = $action->before('Controller@');
        $this->method = $action->after('@');
    }

    abstract protected function render();

    protected function renderTemplate()
    {
        $template = $this->template;

        if (!$template) {
            $template = sprintf('%s/%s', $this->controller->plural(), $this->method->studly());
        }

        return $template;
    }

    protected function wrapData()
    {
        $data = $this->data;

        $data = method_exists($data, 'toArray') ? $data->toArray(request()) : $data;

        return $this->qualifiedWrapper() ? [$this->qualifiedWrapper() => $data] : $data;
    }

    protected function qualifiedWrapper()
    {
        $wrap = $this->wrap;
        if ($wrap === null) {
            $wrap = $this->controller->snake()
                ->when(in_array($this->method, ['index', 'list', 'overview']), fn($string) => $string->plural())
                ->toString();
        }

        return $wrap;
    }
}
