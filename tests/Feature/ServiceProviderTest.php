<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\CiphrService;
use spkm\Ciphr\Facades\Ciphr;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

it('binds the service as a singleton behind the facade', function () {
    expect(app('ciphr'))->toBeInstanceOf(CiphrService::class)
        ->and(app('ciphr'))->toBe(app('ciphr'))
        ->and(Ciphr::getFacadeRoot())->toBe(app('ciphr'));
});

it('loads the config with its environment names', function () {
    expect(config('ciphr'))->toHaveKeys(['portal', 'api_key', 'base_url', 'connections', 'timeouts', 'retries', 'cache', 'pagination'])
        ->and(config('ciphr.pagination.per_page'))->toBe(100)
        ->and(file_get_contents(__DIR__.'/../../config/ciphr.php'))->toContain("env('CIPHR_PORTAL')", "env('CIPHR_API_KEY')", "env('CIPHR_BASE_URL')", "env('CIPHR_RETRIES')", "env('CIPHR_CACHE', false)");
});

it('publishes the config with the install command', function () {
    $published = config_path('ciphr.php');
    File::delete($published);

    Artisan::call('ciphr:install', ['--no-interaction' => true]);

    expect(File::exists($published))->toBeTrue();
    File::delete($published);
});

it('builds the default connector from config the first time it is used', function () {
    config()->set('ciphr.portal', 'school');
    config()->set('ciphr.api_key', 'secret');
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);

    Ciphr::send(new GetExamplesRequest);

    expect(FakeCiphr::received($mockClient, GetExamplesRequest::class)->getUrl())->toBe('https://school.app.ciphr.com/api/Example')
        ->and(FakeCiphr::received($mockClient, GetExamplesRequest::class)->headers()->get('Authorization'))->toBe('apikey secret');
});

it('says which setting the default connection is missing', function (string $setting) {
    config()->set('ciphr.portal', 'school');
    config()->set('ciphr.api_key', 'secret');
    config()->set("ciphr.$setting", '');

    expect(fn () => Ciphr::send(new GetExamplesRequest))->toThrow(InvalidArgumentException::class, "The default Ciphr connection needs a $setting.");
})->with(['portal', 'api_key']);

it('uses a base URL override from config', function () {
    config()->set('ciphr', ['portal' => 'school', 'api_key' => 'secret', 'base_url' => 'https://hr.example.test/api']);
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);

    Ciphr::send(new GetExamplesRequest);

    expect(FakeCiphr::received($mockClient, GetExamplesRequest::class)->getUrl())->toBe('https://hr.example.test/api/Example');
});

it('gives a named connection its own connector, once', function () {
    config()->set('ciphr.connections.other', ['portal' => 'other', 'api_key' => 'other-key']);

    $connection = Ciphr::connection('other');

    expect($connection)->toBeInstanceOf(CiphrConnector::class)
        ->and($connection->resolveBaseUrl())->toBe('https://other.app.ciphr.com/api')
        ->and(Ciphr::connection('other'))->toBe($connection)
        ->and(fn () => Ciphr::connection('missing'))->toThrow(InvalidArgumentException::class, 'No Ciphr connection named "missing"');

    config()->set('ciphr.connections.broken', ['portal' => 'x']);
    expect(fn () => Ciphr::connection('broken'))->toThrow(InvalidArgumentException::class, 'The Ciphr connection "broken" needs a api_key.');
});

it('refuses a method the connector does not have', function () {
    config()->set('ciphr.portal', 'school');
    config()->set('ciphr.api_key', 'secret');

    expect(fn () => Ciphr::nothing())->toThrow(BadMethodCallException::class, 'Method nothing does not exist.');
});
