<?php

namespace DefStudio\ProductionRibbon;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class ProductionRibbon
{
    /** @var array<int, Closure> */
    private static array $filterUsing = [];

    public static function filter(Closure $callback): ProductionRibbon
    {
        ProductionRibbon::$filterUsing[] = $callback;

        return new ProductionRibbon;
    }

    public static function resetFilters(): ProductionRibbon
    {
        ProductionRibbon::$filterUsing = [];

        return new ProductionRibbon;
    }

    public function isActive(): bool
    {
        if (! config('production-ribbon.enabled', true)) {
            return false;
        }

        if (! in_array($this->environment(), config('production-ribbon.environments'))) {
            return false;
        }

        $user = Auth::user();

        if (! $user instanceof User) {
            return false;
        }

        return $this->isUserEnabled($user);
    }

    private function isUserEnabled(Authenticatable $user): bool
    {
        foreach (ProductionRibbon::$filterUsing as $filter) {
            if ($filter($user)) {
                return true;
            }
        }

        foreach (Arr::wrap(config('production-ribbon.filters.email', [])) as $emailPattern) {
            /** @phpstan-ignore-next-line */
            if ($user->email === $emailPattern) {
                return true;
            }

            $emailPattern = Str::of($emailPattern);
            if ($emailPattern->contains('*')) {
                if (Str::of($user->email ?? '')->endsWith($emailPattern->afterLast('*'))) {
                    return true;
                }
            }
        }

        return in_array(request()->ip(), Arr::wrap(config('production-ribbon.filters.ip', [])), true);
    }

    public function inject(mixed $response)
    {
        if (! $response instanceof Response) {
            return $response;
        }

        $content = $response->getContent();

        if ($content === false) {
            return $response;
        }

        if ($headEnd = mb_strpos($content, '</head>')) {
            $styles = file_get_contents(__DIR__.'/../resources/css/styles.css');

            $content = Str::of(mb_substr($content, 0, $headEnd))
                ->append("<style>\n$styles\n</style>")
                ->append(mb_substr($content, $headEnd))
                ->toString();

            if ($bodyEnd = mb_strpos($content, '</body>')) {
                $position = config('production-ribbon.position', 'left');

                $content = Str::of(mb_substr($content, 0, $bodyEnd))
                    ->append(<<<HTML
                        <div id="production-ribbon" class="$position">&nbsp;&nbsp;&nbsp;{$this->environment()}&nbsp;&nbsp;&nbsp;</div>
                    HTML
                    )
                    ->append(mb_substr($content, $bodyEnd))
                    ->toString();
            }

            $response->setContent($content);
        }

        return $response;
    }

    private function environment(): string
    {
        return config('app.env');
    }
}
