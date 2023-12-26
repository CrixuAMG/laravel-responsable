<?php

namespace CrixuAMG\Responsable\Services;

use Illuminate\Support\Str;

class ForwardsConfiguration
{
    private static array $config = [];

    public static function __callStatic(string $name, array $arguments)
    {
        $string = Str::of($name);
        if ($string->startsWith('set')) {
            self::$config[$string->after('set')->camel()->toString()] = reset($arguments);
        }
        if ($string->startsWith('get')) {
            return self::$config[$string->after('get')->camel()->toString()] ?? null;
        }
    }
}
