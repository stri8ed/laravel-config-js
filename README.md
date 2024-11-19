# Laravel Config JS

A Laravel package that exposes your Laravel configuration values to JavaScript.

## Installation

```bash
composer require stri8ed/laravel-config-js
```

## Usage

In your Blade views, use the `@configJs` directive to expose specific config values:

```php
@configJs('app.name')
```

This creates a global `laravelConfig()` function that you can use in JavaScript:

```javascript
// Get a single config value
const appName = laravelConfig('app.name');

// With a default value
const debug = laravelConfig('app.debug', false);
```

You can expose multiple config values:

```php
@configJs(['app.name', 'app.env', 'services.api.key'])
```

## Configuration

Optionally publish the config file to customize the JavaScript function name:

```bash
php artisan vendor:publish --tag="config-js"
```

## License

MIT