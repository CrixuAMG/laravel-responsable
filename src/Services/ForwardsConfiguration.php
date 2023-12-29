<?php

namespace CrixuAMG\Responsable\Services;

use Illuminate\Support\Str;

/**
 * @method getTemplateRoot()
 * @method setTemplateRoot(string $templateRoot)
 * @method getWithoutWrapping()
 * @method setWithoutWrapping(bool $withoutWrapping = true)
 */
class ForwardsConfiguration
{
    private static array $config = [];

    public static function __callStatic(string $name, array $arguments)
    {
        $string = Str::of($name);
        if ($string->startsWith('set')) {
            $value = !empty($arguments) ? reset($arguments) : true;
            self::$config[$string->after('set')->camel()->toString()] = $value;
        }
        if ($string->startsWith('get')) {
            return self::$config[$string->after('get')->camel()->toString()] ?? null;
        }
    }
}
