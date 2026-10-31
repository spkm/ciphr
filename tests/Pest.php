<?php

use Saloon\Config;
use spkm\Ciphr\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Every test boots the package in Testbench with an array cache. No request may reach a real Ciphr: anything sent
| without a mock client throws a StrayRequestException. Fake Ciphr with Tests\Helpers\FakeCiphr.
|
*/

Config::preventStrayRequests();

pest()->extend(TestCase::class)->in('Feature', 'Spec');
