<?php

namespace Stri8ed\LaravelConfigJs\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Stri8ed\LaravelConfigJs\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }
}