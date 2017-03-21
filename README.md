# Form for Laravel 5

## Install

Via Composer

``` bash
$ composer require webmachine/form
```

Next, you must install the service provider and facade alias:

```php
// config/app.php
'providers' => [
    ...
    Webmachine\Form\FormServiceProvider::class,
];

...

'aliases' => [
    ...
    'Form' => Webmachine\Form\FormFacade::class,
];
```

Publish

``` bash
$ php artisan vendor:publish --provider="Webmachine\Form\FormServiceProvider"
```

## Usage

In your views
``` blade
{!! Form::text('mytext', ['class' => 'myclass']) !!}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
