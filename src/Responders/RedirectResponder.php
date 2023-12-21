<?php

namespace CrixuAMG\Responsable\Responders;

use Illuminate\Routing\Route;
use Illuminate\Contracts\Support\Responsable;

class RedirectResponder extends AbstractResponder implements Responsable
{
    private string $route;
    private array $parameters;
    private int $statusCode;
    private array $headers;

    public function toResponse($request)
    {
        /** @var Route $route */;
        $route = app('router')
            ->getRoutes()
            ->getByName($this->route)
            ->bind($request);

        $data = array_merge($this->wrapData(), $this->parameters);
        $bindingFields = $route->bindingFields();

        foreach ($route->parameterNames() as $parameterName) {
            $dataForParameter = $data[$parameterName] ?? null;

            if (!$dataForParameter) continue;

            if (isset($bindingFields[$parameterName])) {
                if (!isset($dataForParameter[$bindingFields[$parameterName]])) {
                    throw new \UnexpectedValueException('Unable to bind route parameter ' . $parameterName);
                }

                $dataForParameter = $dataForParameter[$bindingFields[$parameterName]];
            } else if (isset($dataForParameter['id'])) {
                $dataForParameter = $dataForParameter['id'];
            }

            $route->setParameter($parameterName, $dataForParameter);
        }

        return to_route(
            $route->getName(),
            $route->parameters(),
            $this->statusCode,
            $this->headers,
        );
    }

    public function setRoute(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function setParameters(array $parameters = []): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }
}
