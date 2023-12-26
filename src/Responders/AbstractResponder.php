<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Stringable;

abstract class AbstractResponder
{
    public $data;
    private $attributes;
    protected Stringable $controller;
    protected Stringable $method;

    public function __construct()
    {
        $action = Str::of(request()->route()->action['controller'])
            ->afterLast('\\');

        $this->controller = $action->before('Controller@');
        $this->method = $action->after('@');
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        $string = Str::of($name);
        if ($string->startsWith('set')) {
            $this->attributes[$string->after('set')->camel()->toString()] = reset($arguments);

            return $this;
        }
        if ($string->startsWith('get')) {
            return $this->attributes[$string->after('get')->camel()->toString()] ?? null;
        }
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    protected function wrapData()
    {
        $data = $this->data;

        if (is_object($data)) {
            if (method_exists($data, 'withoutWrapping')) $data->withoutWrapping();
            $data = method_exists($data, 'resolve') ? $data->resolve(request()) : $data;
        }

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
