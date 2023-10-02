<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;

abstract class AbstractResponder implements Responsable
{
    public $data;
    private $attributes;

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
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __toString(): string
    {
        return $this->toResponse(app()->make(Request::class));
    }
}
