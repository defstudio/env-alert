<?php

namespace DefStudio\ProductionRibbon\Middleware;

use Closure;
use DefStudio\ProductionRibbon\ProductionRibbon;

final class InjectRibbon
{
    public function __construct(private readonly ProductionRibbon $ribbon)
    {
    }

    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);

        if (! $this->ribbon->isActive()) {
            return $response;
        }

        return $this->ribbon->inject($response);
    }
}
