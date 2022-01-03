# Metamodel Eloquent for Bonsai CMS

## Installation

You can install the package via composer:

```bash
composer require bonsaicms/metamodel-eloquent
```

### Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag="bonsaicms-metamodel-eloquent-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Customizing Stubs

You can publish stub files with:

```bash
php artisan vendor:publish --tag="bonsaicms-metamodel-eloquent-stubs"
```

Then, you can edit stub files in `resources/stubs/metamodel-eloquent/` folder.

## Commands

Generate missing models:

```bash
php artisan metamodel:generate-models
```

Regenerate all models:

```bash
php artisan metamodel:regenerate-models
```

Delete all generated models:

```bash
php artisan metamodel:delete-models
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
