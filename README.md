# laravel-observable

![Banner](https://banners.beyondco.de/Laravel%20Observable.png?theme=light&packageManager=composer+require&packageName=lorenzofk%2Flaravel-observable&pattern=architect&style=style_1&description=Observe+your+models+automatically&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

## Installation

You can install the package via composer:

```bash
composer require lorenzofk/laravel-observable
```

## Why to use?

I've decided to create this tool to solve a problem we were facing on a big project I work on. 

Usually, we have to define the Model and its Observer in the `app/Providers/AppServiceProvider.php` class or even to create a specific `Provider` to define this relationship.

With this `Trait` now we are able to define the `Observer` in the `Model` itself, which is much easier and quicker to do.

## Usage

Use the `Observable` trait in any Eloquent Model, you must also add the `$observer` property. This property must be a `string` or an `array`.

```php

use lorenzofk\Observable\Observable;

class User extends Model
{
    use Observable;

    protected $observer = UserObserver::class;
}

```

And that's it! You don't need to do anything else.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Lorenzo Kniss](https://github.com/lorenzofk)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
