<?php

namespace Stri8ed\LaravelConfigJs\Tests\Unit;

use Stri8ed\LaravelConfigJs\Directive;
use Stri8ed\LaravelConfigJs\Tests\TestCase;

class DirectiveTest extends TestCase
{
    public function test_compiles_single_config_key()
    {
        config(['app.name' => 'Laravel Test']);

        $result = Directive::compile('app.name');


        $this->assertStringContainsString('<script type="text/javascript">', $result);
        $this->assertStringContainsString('{"app.name":"Laravel Test"}', $result);
        $this->assertStringContainsString('laravelConfig', $result);
    }

    public function test_compiles_multiple_config_keys()
    {
        config([
            'app.name' => 'Laravel Test',
            'app.env' => 'testing'
        ]);

        $result = Directive::compile('["app.name", "app.env"]');

        $this->assertStringContainsString('"app.name":"Laravel Test"', $result);
        $this->assertStringContainsString('"app.env":"testing"', $result);
    }

    public function test_handles_nested_config_values()
    {
        config(['nested' => [
            'key1' => [
                'key2' => 'value'
            ]
        ]]);

        $result = Directive::compile('nested');

        $this->assertStringContainsString('"nested":{"key1":{"key2":"value"}}', $result);
    }

    public function test_uses_custom_function_name_from_config()
    {
        config(['config-js.function_name' => 'customConfig']);
        config(['app.name' => 'Laravel Test']);

        $result = Directive::compile('app.name');

        $this->assertStringContainsString('window.customConfig', $result);
    }
}