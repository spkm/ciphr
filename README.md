![Banner](https://banners.beyondco.de/spkm%5Cciphr.png?theme=dark&packageManager=composer+require&packageName=spkm%2Fciphr&pattern=temple&style=style_1&description=&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

## Installation and usage
This package requires PHP 8.3 & Laravel 11.0 or higher & Ciphr 10 or higher. See the `tests/` folder for documentation.

### Basic Installation:
You can install this package via composer using:
```
composer require spkm/ciphr
```

### Usage

```php
use spkm\ciphr\CiphrConnector;
use spkm\ciphr\Requests\Person\GetPersonDetailsRequest;

$connector = new CiphrConnector($yourCustomerPortal, $yourApiKey);
$request = new GetPersonDetailsRequest();
$paginator = $connector->paginate($request);

foreach ($paginator as $response) {
  $response->json();
}
```

### Security

If you discover any security related issues, please email hello@spkmitchell.co.uk instead of using the issue tracker.

## Credits

- [Simon Mitchell](https://github.com/spkm)
- [Fred Bradley](https://github.com/fredbradley)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
