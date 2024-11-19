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
        $this->assertStringContainsString(json_encode(['app' => ['name' => 'Laravel Test']]), $result);
        $this->assertStringContainsString('laravelConfig', $result);
    }

    public function test_compiles_multiple_config_keys()
    {
        config([
            'app.name' => 'Laravel Test',
            'app.env' => 'testing'
        ]);

        $result = Directive::compile('["app.name", "app.env"]');

        $expectedJson = json_encode(['app' => ['name' => 'Laravel Test', 'env' => 'testing']]);
        $this->assertStringContainsString($expectedJson, $result);
    }

    public function test_handles_nested_config_values()
    {
        config(['nested' => [
            'key1' => [
                'key2' => 'value'
            ]
        ]]);

        $result = Directive::compile('nested');

        $expectedJson = json_encode(['nested' => ['key1' => ['key2' => 'value']]]);
        $this->assertStringContainsString($expectedJson, $result);
    }

    public function test_uses_custom_function_name_from_config()
    {
        config(['config-js.function_name' => 'customConfig']);
        config(['app.name' => 'Laravel Test']);

        $result = Directive::compile('app.name');

        $this->assertStringContainsString('window.customConfig', $result);
    }

    public function test_compiles_deeply_nested_single_key()
    {
        config(['aws.services.lambda.url' => 'https://lambda.aws.com']);

        $result = Directive::compile('aws.services.lambda.url');

        $expectedJson = json_encode([
            'aws' => [
                'services' => [
                    'lambda' => [
                        'url' => 'https://lambda.aws.com'
                    ]
                ]
            ]
        ]);
        $this->assertStringContainsString($expectedJson, $result);
    }

    public function test_compiles_multiple_nested_keys_under_same_parent()
    {
        config([
            'aws.lambda.url' => 'https://lambda.aws.com',
            'aws.lambda.region' => 'us-east-1',
            'aws.s3.bucket' => 'my-bucket'
        ]);

        $result = Directive::compile('["aws.lambda.url", "aws.lambda.region", "aws.s3.bucket"]');

        $expectedJson = json_encode([
            'aws' => [
                'lambda' => [
                    'url' => 'https://lambda.aws.com',
                    'region' => 'us-east-1'
                ],
                's3' => [
                    'bucket' => 'my-bucket'
                ]
            ]
        ]);
        $this->assertStringContainsString($expectedJson, $result);
    }

    public function test_handles_null_config_values()
    {
        config(['app.nullable' => null]);

        $result = Directive::compile('app.nullable');

        $expectedJson = json_encode(['app' => ['nullable' => null]]);
        $this->assertStringContainsString($expectedJson, $result);
    }
}