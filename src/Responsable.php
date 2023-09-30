<?php

namespace CrixuAMG\Responsable;

use \Illuminate\Contracts\Support\Responsable as ResponsableContract;

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

        throw new \LogicException('Unable to resolve request response type.');
    }

    public function json()
    {
        return $this->manager()
            ->driver('json')
            ->setData($this->data);
    }

    public function view(callable $action)
    {
        $actionResult = call_user_func($action, $this->data);

        return $this->manager()
            ->driver(config('responsable.view_driver'))
            ->setData($actionResult);
    }

    public function redirect(callable $action)
    {
        return $this->manager()
            ->driver('redirect')
            ->setAction($action)
            ->setData($this->data);
    }
}
