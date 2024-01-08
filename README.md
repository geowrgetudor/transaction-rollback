<p align="center"><img src="/transaction-rollbacks.png" alt="Transaction rollback tracker for Laravel Pulse"></p>

# Track Laravel database transaction rollbacks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/geowrgetudor/transaction-rollback.svg?style=flat-square)](https://packagist.org/packages/geowrgetudor/transaction-rollback)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/geowrgetudor/transaction-rollback/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/geowrgetudor/transaction-rollback/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/geowrgetudor/transaction-rollback/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/geowrgetudor/transaction-rollback/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/geowrgetudor/transaction-rollback.svg?style=flat-square)](https://packagist.org/packages/geowrgetudor/transaction-rollback)

Track the rollbacked database transaction in Laravel Pulse.

## Installation

You can install the package via composer:

```bash
composer require geowrgetudor/transaction-rollback
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="transaction-rollback-views"
```

## Usage

Register the recorder inside `config/pulse.php`. (If you don\'t have this file make sure you have published the config file of Laravel Pulse using `php artisan vendor:publish --tag=pulse-config`)

```
return [
    // ...

    'recorders' => [
        // Existing recorders...

        \Geow\TransactionRollback\Recorders\TransactionRollbackRecorder::class => [
            'enabled' => env('GEOW_TRANSACTION_ROLLBACK', true),
            'ignore' => [
                // Ignore connections or databases.
            ],
        ]
    ]
]
```

Rollbacked queries will be recorded ONLY if you use query logging `DB::enableQueryLog()` before starting a transaction.

Publish Laravel Pulse `dashboard.blade.php` view using `php artisan vendor:publish --tag=pulse-dashboard`

Then you can modify the file and add the transaction-rollbacks livewire template.

```php
<livewire:transaction-rollbacks cols="full" />
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [George Tudor](https://github.com/geowrgetudor)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
