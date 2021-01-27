# WolfAPI Authentication

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ignitionwolf/wolf-api-auth.svg?style=flat-square)](https://packagist.org/packages/ignitionwolf/wolf-api-auth)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ignitionwolf/wolf-api-auth/Tests?label=tests)](https://github.com/ignitionwolf/wolf-api-auth/actions?query=workflow%3ATests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/ignitionwolf/wolf-api-auth.svg?style=flat-square)](https://packagist.org/packages/ignitionwolf/wolf-api-auth)


This scaffolds the authentication boilerplate for projects powered by WolfAPI.

## vs Laravel Fortify

Contrary to Fortify, this package bootstraps the authentication code in your project, this means you will be able to 
implement business logic and modify the authentication process as you wish. This may require a lower level understanding 
of how authentication works, considering Fortify hides most of the process. Moreover, this is meant to integrate nicely 
with WolfAPI and ease the API development. However, you may use Fortify or any other solution you prefer to use.

## Installation

You can install the package via composer:

```bash
composer require --dev IgnitionWolf/wolf-api-auth
```

## Usage

```bash
php artisan api:auth
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [IgnitionWolf](https://github.com/IgnitionWolf)
- [Nicolas Widart](https://github.com/nWidart) - code for stubs and file generators.
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
