# Production Ribbon

[![Latest Version on Packagist](https://img.shields.io/packagist/v/defstudio/production-ribbon.svg?style=flat-square)](https://packagist.org/packages/defstudio/production-ribbon)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/production-ribbon/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/defstudio/production-ribbon/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/defstudio/production-ribbon/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/defstudio/production-ribbon/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/defstudio/production-ribbon.svg?style=flat-square)](https://packagist.org/packages/defstudio/production-ribbon)

Show a nice red alert ribbon when your user is in a production environment

## Installation

You can install the package via composer:

```bash
composer require defstudio/production-ribbon
```

You should publish the config file with:

```bash
php artisan vendor:publish --tag="production-ribbon-config"
```

This is the contents of the published config file:

```php
return [
    'enabled' => !!env('PRODUCTION_RIBBON_ENABLED', true),

    /*
     * Environments where to show the ribbon alert
     */
    'environments' => ['production'],

    /*
     * When to display the ribbon alert
     */
    'users' => [
        'emails' => ['your.email@email.test', '*@your.company.com'],
        'usernames' => ['administrator'],
        'ip' => ['123.456.789.101'],
    ]
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="production-ribbon-views"
```

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
