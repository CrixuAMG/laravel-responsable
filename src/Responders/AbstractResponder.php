<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

abstract class AbstractResponder
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
}
