<?php

namespace CrixuAMG\Responsable;

class Responsable
{
    public function __construct(private $data) { }

    private function manager(): ResponsableManager
    {
        return app()->make(ResponsableManager::class);
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
