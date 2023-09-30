<?php

namespace CrixuAMG\Responsable\Responders;

class BladeResponder extends AbstractResponder
{
    public function toResponse($request)
    {
        $template = $this->data[0] ?? null;

        if (!is_string($template)) throw new \InvalidArgumentException('First argument must be a string');
        $data = $this->data;
        array_shift($data);

        return view($template, ...$data);
    }
}
