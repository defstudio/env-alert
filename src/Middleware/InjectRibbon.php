<?php

namespace DefStudio\ProductionRibbon\Middleware;

use Closure;
use DefStudio\ProductionRibbon\ProductionRibbon;

final class InjectRibbon
{
    private ProductionRibbon $ribbon;

    public function __construct()
    {
        $ribbonClass = config('production-ribbon.service_class');
        $this->ribbon = app($ribbonClass);
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
