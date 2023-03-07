<?php

namespace DefStudio\EnvAlert\Middleware;

use Closure;
use DefStudio\EnvAlert\AlertService;

final class InjectEnvAlert
{
    private readonly AlertService $ribbon;

    public function __construct()
    {
        /** @var class-string<AlertService> $ribbonClass */
        $ribbonClass = config('env-alert.service_class');
        $this->ribbon = app($ribbonClass);
    }

    public function handle(mixed $request, Closure $next): mixed
    {
        $response = $next($request);

        if (! $this->ribbon->isActive()) {
            return $response;
        }

        return $this->ribbon->inject($response);
    }
}
