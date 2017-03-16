# FormField for Laravel 5

## Install

Via Composer

``` bash
$ composer require webmachine/form-field
```

Next, you must install the service provider and facade alias:

```php
// config/app.php
'providers' => [
    ...
    Webmachine\FormField\FormFieldServiceProvider::class,
];

...

'aliases' => [
    ...
    'Logs' => Webmachine\FormField\FormFieldFacade::class,
];
```

Publish

``` bash
$ php artisan vendor:publish --provider="Webmachine\FormField\FormFieldServiceProvider"
```

## Usage

In your views
``` blade
{!! FormField::text('mytext', ['class' => 'myclass']) !!}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
