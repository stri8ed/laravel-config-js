<?php

namespace Stri8ed\LaravelConfigJs\Tests\Unit;

use Stri8ed\LaravelConfigJs\Directive;
use Stri8ed\LaravelConfigJs\ServiceProvider;
use Stri8ed\LaravelConfigJs\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_config_is_merged()
    {
        $this->assertEquals('laravelConfig', config('config-js.function_name'));
    }

    public function test_config_can_be_published()
    {
        $provider = app()->getProvider(ServiceProvider::class);
        $publishes = $provider->publishableGroups();

        $this->assertContains('config-js', $publishes);

        $publishArray = $provider->pathsToPublish(null, 'config-js');
        $this->assertNotNull($publishArray);

        $sourcePath = key($publishArray);
        $destPath = current($publishArray);

        $this->assertStringEndsWith('config/config-js.php', $sourcePath);
        $this->assertEquals(config_path('config-js.php'), $destPath);
    }

    public function test_blade_directive_is_registered()
    {
        $directives = app('blade.compiler')->getCustomDirectives();

        $this->assertArrayHasKey('configJs', $directives);
        $this->assertEquals([Directive::class, 'compile'], $directives['configJs']);
    }

    public function test_config_merging_respects_user_config()
    {
        config(['config-js.function_name' => 'customConfig']);

        $provider = new ServiceProvider($this->app);
        $provider->register();

        $this->assertEquals('customConfig', config('config-js.function_name'));
    }
}