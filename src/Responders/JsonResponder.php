<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Resources\Json\JsonResource;

class JsonResponder extends AbstractResponder implements Responsable
{
    public function toResponse($request)
    {
        if ($this->data instanceof JsonResource) {
            return $this->data->toResponse($request);
        }

        return json_encode($this->data);
    }
}
