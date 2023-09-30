<?php

namespace CrixuAMG\Responsable\Responders;

use Inertia\Inertia;

class InertiaResponder extends AbstractResponder
{
    public function toResponse($request)
    {
        $template = $this->data[0] ?? null;

        if (!is_string($template)) throw new \InvalidArgumentException('First argument must be a string');
        $data = $this->data;
        array_shift($data);

        return Inertia::render($template, ...$data)->toResponse($request);
    }
}
