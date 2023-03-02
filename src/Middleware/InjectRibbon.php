<?php

namespace DefStudio\ProductionRibbon\Middleware;

use Closure;
use DefStudio\ProductionRibbon\ProductionRibbon;

class InjectRibbon
{
    public function __construct(private ProductionRibbon $ribbon)
    {
    }

    public function handle($request, Closure $next): mixed
    {
        if (! $this->ribbon->isActive()) {
            return $next($request);
        }

        $response = $next($request);

        return $this->ribbon->inject($response);
    }
}
