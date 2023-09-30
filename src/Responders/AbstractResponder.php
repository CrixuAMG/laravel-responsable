<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Contracts\Support\Responsable;

abstract class AbstractResponder implements Responsable
{
    public $data;

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
