# Environment Ribbon

<a href="https://packagist.org/packages/defstudio/env-alert" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/v/defstudio/env-alert.svg?style=flat&cacheSeconds=3600" alt="Latest Version on Packagist">
</a>

<a href="https://github.com/defstudio/env-alert/actions?query=workflow%3Arun-tests+branch%3Amain" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/env-alert/run-tests.yml?branch=main&label=tests&cacheSeconds=3600&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAMAAABF0y+mAAABiVBMVEUAAAD/iPv9yP3Xm+j/mP//wfVj67Je6bP/h/pVx6p6175d57WQycf+iPn/iPrsnezArd3+t/qpvNJd6LP/jPpu6rv/lPr/kPpc57T/rvtc57Np6rj3oPl37cL/tfn/wv9d6brX//L/g/rYn+n/gvrWm+di6LX+jPrskfGWzMpt6bln4bdd57Jk6LWSycj+vPquwNVo6rde6bP7nvvYnup91b/+vfv/lvtc57OqvNTFs9//t/td57L9t/r/iPpd6LPapej/ovp26bxy67v9lfld6LJr4Ljwsvb/xv3/jv39zv1t6buG5cTDreH5ivlc5rJy676V4cxb57D/y/h50MOy4OCUxcVa77X/iPpe6LP/jP+pu9L8t///tvuQycfArNxp6LzArd151r7/i/9n4bb/j/9e6rT/ifr7ifrskvLYnuhi87tg8blg7bf/vv//lP+wxNtj9b3/qv//oP/+ivz/l/r8ifryn/fvlPTfpPDeofDKtujHtOWX1NF/4seC3cR82sFu7cBo5LiMwPMrAAAAWHRSTlMA/Wv8FAIC/dME/Wj+3tEG/Pv798G1oHRjS0k1LBsWDgsJ/v36+fTy8ezn4+Lh29XNzMzLysLAwLSwr66opJqakY+Ni4J7end0bGlpY11XU048KicmIR8fizl+vwAAAVdJREFUKM9tz2VXAlEQgOFBBURpkE67u7u7E1YFYQl1SbvrlztDiLvss+fc/fCemXMvAEhhKqU759P1rLoxUDUyEh9fPH0z7ALiVrEY+SSNtxNS2upouYv7hOL191aKVsZHUTgbnQPQgDkq4ctHdoQmTWmW4WFzlVUDVpNKXf2fWpWbZIwUq/hcmjWGYnSa1pZZjEoomrEdVAisD7CX6GEb40rqTODxCj21OjDOvjRV8l2jhudBDchg/FUbDIZCITzwQyH6a9+2AMDbm9GfltFnxgAdtQWUgQJl4VQq37uPcSnsfYZzav6Ew18fQ4fUYPM7Qn4uSiIdyx5saJ6T+/3+5KSltshicwI2UpfAKE/aoARTvnn7KMYMdlAUyWRSyHN2JeU42HlCi4TszTHcmuj3iMVdP5JzoyAWNzi6T3ZGrMFCliK3BAqRSC/B2+6IxvYYNcO+2Npfv+yFi10LfBUAAAAASUVORK5CYII=" alt="Tests">
</a>

<a href="https://github.com/defstudio/env-alert/actions?query=workflow%3Alint+branch%3Amain" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/env-alert/fix-php-code-style-issues.yml?branch=main&label=code%20style&cacheSeconds=3600" alt="Code Style">
</a>

<a href="https://github.com/defstudio/env-alert/actions?query=workflow%3Aphpstan+branch%3Amain" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/github/actions/workflow/status/defstudio/env-alert/phpstan.yml?branch=main&label=phpstan&cacheSeconds=3600" alt="Static Analysis">
</a>

<a href="https://packagist.org/packages/defstudio/env-alert" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/dt/defstudio/env-alert.svg?style=flat&cacheSeconds=3600" alt="Total Downloads">
</a>

<a href="https://packagist.org/packages/defstudio/env-alert" target="_blank">
    <img style="display: inline-block; margin-top: 0.5em; margin-bottom: 0.5em" src="https://img.shields.io/packagist/l/defstudio/env-alert?style=flat&cacheSeconds=3600" alt="License">
</a>

Show a nice red alert ribbon when **you** (and only you) are in a production environment

![image](https://user-images.githubusercontent.com/8792274/222460043-dc3e3297-2c59-4d78-9092-feb01efa22bf.png)


## Installation

You can install the package via composer:

```bash
composer require defstudio/env-alert
```

You should publish the config file with:

```bash
php artisan vendor:publish --tag="env-alert-config"
```

This is the contents of the published config file:

```php
return [
        'enabled' => (bool) env('ENV_ALERT_ENABLED', true),

    /*
     * Current environment, taken from APP_ENV or ENV_ALERT_ENV .env entries
     */
    'current_environment' => env('ENV_ALERT_ENV', env('APP_ENV', 'production')),

    /*
     * Environments where to show the ribbon alert
     */
    'environments' => [
        'production' => [
            /*
             * When to display the ribbon alert.
             */
            'filters' => [
                'email' => [
                    // 'your.email@email.test',
                    // '*@your.company.com'
                ],
                'ip' => [
                    // '123.456.789.101'
                ],
            ],

            /*
             * The ribbon style
             */
            'style' => [
                'position' => 'right',
                'background_color' => '#f30b0b',
                'text_color' => '#ffffff',
            ],
        ],
    ],

    'service_class' => \DefStudio\EnvAlert\AlertService::class,
];
```

That's all, a red ribbon on the top right corner of the screen will warn when you are operating in a production environment!

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. [Follow Us](https://twitter.com/FabioIvona) on Twitter for more updates about this package.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fabio Ivona](https://github.com/defstudio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
