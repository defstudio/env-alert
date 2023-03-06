<?php

/** @noinspection PhpUndefinedFieldInspection */

namespace DefStudio\EnvAlert;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RibbonService
{
    /** @var array<int, callable(Authenticatable|null): bool> */
    protected static array $filterUsing = [];

    /**
     * @param callable(Authenticatable|null $user): bool $callback
     */
    public static function filter(callable $callback): RibbonService
    {
        RibbonService::$filterUsing[] = $callback;

        return new RibbonService;
    }

    public static function resetFilters(): RibbonService
    {
        RibbonService::$filterUsing = [];

        return new RibbonService;
    }

    public function isActive(): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        if (! $this->isEnvironmentEnabled()) {
            return false;
        }

        if ($this->isEnabledByIp()) {
            return true;
        }

        $user = $this->getCurrentUser();

        if ($this->isEnabledByCustomFilters($user)) {
            return true;
        }

        /** @phpstan-ignore-next-line  */
        return $user instanceof Authenticatable && $this->isEnabledByEmail($user->email);
    }

    /**
     * @template TResponse
     *
     * @param  TResponse  $response
     * @return TResponse
     */
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
                $content = Str::of(mb_substr($content, 0, $bodyEnd))
                    ->append(view('ribbon::ribbon', [
                        'position' => $this->getConfig('style.position', 'left'),
                        'bgColor' => $this->getConfig('style.background_color', '#f30b0b'),
                        'textColor' => $this->getConfig('style.text_color', '#ffffff'),
                        'text' => $this->getConfig('text', $this->environment()),
                    ])->render())
                    ->append(mb_substr($content, $bodyEnd))
                    ->toString();
            }

            $response->setContent($content);
        }

        return $response;
    }

    protected function environment(): string
    {
        $env = config('env-alert.current_environment', config('app.env'));
        assert(is_string($env));

        return $env;
    }

    protected function getCurrentUser(): Authenticatable|null
    {
        return Auth::user();
    }

    protected function isEnabled(): bool
    {
        return (bool) $this->getConfig('enabled', true);
    }

    protected function isEnvironmentEnabled(): bool
    {
        $environments = $this->getConfig('environments', []);

        assert(is_array($environments));
        if (Arr::isAssoc($environments)) {
            $environments = array_keys($environments);
        }

        return in_array($this->environment(), $environments);
    }

    protected function isEnabledByCustomFilters(Authenticatable|null $user): bool
    {
        foreach (RibbonService::$filterUsing as $filter) {
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
            if (! $emailPattern->contains('*')) {
                continue;
            }
            if (! Str::of($email)->endsWith($emailPattern->afterLast('*'))) {
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

    protected function getConfig(string $key, mixed $default = null): mixed
    {
        return config(
            'env-alert.environments.'.$this->environment().".$key",
            config("env-alert.$key", $default));
    }
}
