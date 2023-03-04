<?php
/** @noinspection PhpUndefinedFieldInspection */

namespace DefStudio\ProductionRibbon;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ProductionRibbon
{
    /** @var array<int, callable(Authenticatable|null $user): bool> */
    protected static array $filterUsing = [];

    /**
     * @param callable(Authenticatable|null $user): bool $callback
     *
     * @return ProductionRibbon
     */
    public static function filter(callable $callback): ProductionRibbon
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
        if (!$this->isEnabled()) {
            return false;
        }

        if (!$this->isEnvironmentEnabled()) {
            return false;
        }

        if ($this->isEnabledByIp()) {
            return true;
        }

        $user = $this->getCurrentUser();

        if ($this->isEnabledByCustomFilters($user)) {
            return true;
        }

        if ($user !== null && $this->isEnabledByEmail($user->email)) {
            return true;
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
            $styles = file_get_contents(__DIR__.'/../resources/css/styles.css');

            $content = Str::of(mb_substr($content, 0, $headEnd))
                ->append("<style>\n$styles\n</style>")
                ->append(mb_substr($content, $headEnd))
                ->toString();

            if ($bodyEnd = mb_strpos($content, '</body>')) {
                $position = $this->getConfig('style.position', 'left');

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

    protected function environment(): string
    {
        return config('production-ribbon.current_environment', config('app.env'));
    }

    protected function getCurrentUser(): Authenticatable|null
    {
        return Auth::user();
    }

    protected function isEnabled(): bool
    {
        return (boolean) $this->getConfig('enabled', true);
    }

    protected function isEnvironmentEnabled(): bool
    {
        $environments = $this->getConfig('environments', []);

        if (Arr::isAssoc($environments)) {
            $environments = array_keys($environments);
        }

        return in_array($this->environment(), $environments);
    }

    protected function isEnabledByCustomFilters(Authenticatable|null $user): bool
    {
        foreach (ProductionRibbon::$filterUsing as $filter) {
            if ($filter($user)) {
                return true;
            }
        }
        return false;
    }

    protected function isEnabledByEmail(string $email): bool
    {
        foreach (Arr::wrap($this->getConfig('filters.email', [])) as $emailPattern) {
            if ($email === $emailPattern) {
                return true;
            }

            $emailPattern = Str::of($emailPattern);
            if (!$emailPattern->contains('*')) {
                continue;
            }
            if (!Str::of($email)->endsWith($emailPattern->afterLast('*'))) {
                continue;
            }

            return true;
        }

        return false;
    }

    protected function isEnabledByIp(): bool
    {
        return in_array(request()->ip(), Arr::wrap($this->getConfig('filters.ip', [])), true);
    }

    protected function getConfig(string $key, mixed $default): mixed
    {
        return config(
            'production-ribbon.environments.' . $this->environment() . ".$key",
            config("production-ribbon.$key", $default));
    }

}
