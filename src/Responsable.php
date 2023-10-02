<?php

namespace CrixuAMG\Responsable;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;

class Responsable implements ResponsableContract
{
    public function __construct(private $data) { }

    private function manager(): ResponsableManager
    {
        return app()->make(ResponsableManager::class);
    }

    public function toResponse($request)
    {
        if ($request->wantsJson()) return $this->json();

        return $this->view();
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
            ->setData($this->data);
    }

    public function redirect(callable $action)
    {
        return $this->manager()
            ->driver('redirect')
            ->setAction($action)
            ->setData($this->data);
    }
}
