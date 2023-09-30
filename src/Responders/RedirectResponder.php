<?php

namespace CrixuAMG\Responsable\Responders;

class RedirectResponder extends AbstractResponder
{
    private \Closure $action;

    public function toResponse($request)
    {
        return call_user_func($this->action, $this->data);
    }

    public function setAction(callable $action)
    {
        $this->action = $action;

        return $this;
    }
}
