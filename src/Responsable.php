<?php

namespace CrixuAMG\Responsable;

use CrixuAMG\Responsable\Responders\RedirectResponder;
use CrixuAMG\Responsable\Services\ForwardsConfiguration;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;

/**
 * @method getTemplateRoot()
 * @method setTemplateRoot(string $templateRoot)
 * @method getWithoutWrapping()
 * @method setWithoutWrapping(bool $withoutWrapping = true)
 */
class Responsable implements ResponsableContract
{
    public function __construct(private $data)
    {
    }

    public function __call(string $name, array $arguments)
    {
        ForwardsConfiguration::$name(...$arguments);

        return $this;
    }

    public static function from(mixed $data = null)
    {
        return new static($data);
    }

    public function render()
    {
        if (request()->wantsJson()) {
            return $this->json();
        }

        return $this->view();
    }

    private function manager(): ResponsableManager
    {
        return app()->make(ResponsableManager::class);
    }

    public function json()
    {
        return $this->manager()
                ->driver('json')
                ->setData($this->data);
    }

    public function view(string $template = null, string|false $wrap = null)
    {
        return $this->manager()
            ->driver(config('responsable.view_driver'))
            ->setTemplate($template)
            ->setWrap($wrap)
            ->setData($this->data)
            ->render();
    }

    public function redirect(string $route, array $parameters = [], int $statusCode = 302, array $headers = []): RedirectResponder
    {
        return $this->manager()
            ->driver('redirect')
            ->setRoute($route)
            ->setParameters($parameters)
            ->setStatusCode($statusCode)
            ->setHeaders($headers)
            ->setData($this->data);
    }

    public function toResponse($request)
    {
        $renderedResponse = $this->render();

        if ($renderedResponse instanceof ResponsableContract) {
            $renderedResponse = $renderedResponse->toResponse($request);
        }

        return $renderedResponse;
    }
}
