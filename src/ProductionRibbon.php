<?php

namespace DefStudio\ProductionRibbon;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class ProductionRibbon
{
    /** @var array<int, Closure> */
    private static array $filterUsing = [];

    /**
     * @param Closure $callback
     *
     * @return ProductionRibbon
     */
    private static function filter(Closure $callback): ProductionRibbon
    {
        ProductionRibbon::$filterUsing[] = $callback;

        return new ProductionRibbon;
    }

    public function isActive(): bool
    {
        if (!config('production-ribbon.enabled', true)) {
            return false;
        }

        if (!in_array($this->environment(), config('production-ribbon.environments'))) {
            return false;
        }

        $user = Auth::user();

        if ($user === null) {
            return false;
        }

        if (!$this->isUserEnabled($user)) {
            return false;
        }

        return true;
    }

    private function isUserEnabled(Authenticatable $user): bool
    {
        foreach (ProductionRibbon::$filterUsing as $filter) {
            if ($filter($user)) {
                return true;
            }
        }

        if (property_exists($user, 'email')) {
            foreach (config('production-ribbon.filters.email', []) as $emailPattern) {
                $emailPattern = Str::of($emailPattern);
                if ($emailPattern->contains('*')) {
                    if (Str::of($user->email)->endsWith($emailPattern->afterLast('*'))) {
                        return true;
                    }
                }

                if ($user->email === $emailPattern->toString()) {
                    return true;
                }
            }
        }


        foreach (config('production-ribbon.filters.username', []) as $username) {
            if ($user->getAuthIdentifier() === $username) {
                return true;
            }
        }

        foreach (config('production-ribbon.filters.ip', []) as $ip) {
            if (request()->ip() === $ip) {
                return true;
            }
        }

        return false;
    }

    public function inject(mixed $response)
    {
        if (!$response instanceof Response) {
            return $response;
        }

        $content = $response->getContent();

        if ($content === false) {
            return $response;
        }

        if ($headEnd = mb_strpos($content, '</head>')) {
            $styles = file_get_contents(__DIR__."/../resources/css/styles.css");
            $content = Str::of(mb_substr($content, 0, $headEnd))
                ->append("<style>\n$styles\n</style>")
                ->append(mb_substr($content, $headEnd))
                ->toString();
        }

        if ($bodyEnd = mb_strpos($content, '</head>')) {
            $content = Str::of(mb_substr($content, 0, $bodyEnd))
                ->append(
                    <<<HTML
                    <div id="production-ribbon">{$this->environment()}</div>
                    HTML
                )
                ->append(mb_substr($content, $bodyEnd))
                ->toString();
        }

        $response->setContent($content);

        return $response;
    }

    private function environment(): string
    {
        return config('app.env');
    }
}
