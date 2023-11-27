<?php

namespace CrixuAMG\Responsable;

use CrixuAMG\Responsable\Responders\RedirectResponder;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;

class Responsable implements ResponsableContract
{
    public function __construct(private $data)
    {
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

    public function redirect(callable $action): RedirectResponder
    {
        return $this->manager()
            ->driver('redirect')
            ->setAction($action)
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
