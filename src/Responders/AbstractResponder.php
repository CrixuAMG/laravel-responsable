<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Arr;
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
        $resolvedResource = [];

        if (is_object($data)) {
            if (method_exists($data, 'withoutWrapping')) {
                $data->withoutWrapping();
            }

            if (method_exists($data, 'resolve')) {
                $resolvedResource = $data->response()->getData(true);

                $data = $resolvedResource['data'];
            }
        }

        $resolvedData = $this->qualifiedWrapper() ? [$this->qualifiedWrapper() => $data] : $data;

        return array_merge(
            $resolvedData,
            is_array($data) && array_is_list($data) && $this->qualifiedWrapper()
                ? [$this->qualifiedWrapper('_meta') => Arr::except($resolvedResource, 'data')]
                : []
        );
    }

    protected function qualifiedWrapper(string $append = null)
    {
        $wrap = $this->wrap;
        if ($wrap === null) {
            $wrap = $this->controller->snake()
                ->when(in_array($this->method, ['index', 'list', 'overview']), fn($string) => $string->plural())
                ->toString();
        }

        if ($append) $wrap = Str::of($wrap)->append($append)->snake()->toString();

        return $wrap;
    }
}
