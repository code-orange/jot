# jot

Jot is a Laravel package that generates RESTful API documentation in Markdown based on PHPDoc.

# Installation

To install jot with composer:

```
composer require code-orange/jot
```

### Laravel 5.5+

If you're using Laravel 5.5 or above, the package will automatically register the `Jot` provider.

### Laravel 5.4

Add `CodeOrange\Jot\JotServiceProvider::class` to the `providers` array in `config/app.php`.

# Configuration

You can use `php artisan vendor:publish` to publish the jot configuration to your application:

```
php artisan vendor:publish --provider=CodeOrange\\Jot\\JotServiceProvider
```

Then, update `config/jot.php`.

# Usage

## Generating documentation

First, document your controller actions with PHPDoc.

```php
class MyController {
	/**
	 * Returns a value
	 *
	 * This method returns a cool value.
	 *
	 * @param mixed $b Some value
	 * @return JsonResponse {
	 *                          "a": 1,
	 *                          "b": "Example"
	 *                      }
	 */
	public function myAction(Request $r) {
		return response()->json([
			'a' => 1,
			'b' => $r->get('b')
		]);
	}
}
```

Then, run `php artisan jot:generate`.
Markdown documenting your API will be printed to your console.

~~~md
## Returns a value

This method returns a cool value.

`GET /example`

### Parameters

| Name | Located in | Description | Type |
| ---- | ---------- | ----------- | ---- |
|b|request|Some value|mixed|
|account|path|||

```json
{
    "a": 1,
    "b": "Example"
}
```
~~~

You can then add this Markdown to whatever you use to publish your docs.
That can just be a Markdown file on GitHub, a wiki, or a self-hosted documentation site.

The Markdown is compatible with [lord/slate](https://github.com/lord/slate), which is what we're using at [Odyssey](https://github.com/odysseyattribution/) to publish our documentation.

## Checking documentation coverage

Jot can also check if all of your public API methods are properly documented, for instance as part of your CI test pipeline.

```
php artisan jot:coverage
```

will exit with an error status code if there is a method in your API that is not documented.

Optionally, you can use `--return` to force all methods to have a documented return type/example.

# Motivation

This project is heavily inspired by [f2m2/apidocs](https://github.com/f2m2/apidocs).
While that project generates excellent documentation, I didn't like the idea of a Laravel app hosting its own documentation (something that can be done statically and that probably has much different access patterns).

Jot allows you to separate generation and publication of your API docs.

Read more about building Jot in [this blogpost](https://blog.code-orange.nl/building-jot-904f4672a75).
