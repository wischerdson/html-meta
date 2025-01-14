<p align="center">
	<img src="https://raw.githubusercontent.com/wischerdson/html-meta/refs/heads/master/docs/logo.svg" alt="HTML meta logo" width="320">
</p>

<p align="center">
	<img src="https://github.com/wischerdson/html-meta/actions/workflows/tests.yml/badge.svg" alt="Tests status">
	<img src="https://badgen.net/github/license/wischerdson/html-meta" alt="License">
</p>

"HTML meta" is a php package for parsing website metadata such as site title, favicons, opengraph and other.

---

## Installation

You can install the package via composer:

```bash
composer require osmuhin/html-meta
```

> [!NOTE]
> You must require the **vendor/autoload.php** file in your code to enable the class autoloading mechanism provided by [Composer](https://getcomposer.org/doc/01-basic-usage.md).

## Basic usage

```php
use Osmuhin\HtmlMeta\Crawler;

$meta = Crawler::init(url: 'https://google.com')->run();

echo $meta->title; // Google
```

Instead of a URL, you can pass raw HTML as string:

```php

$html = <<<END
<html lang="en">
	<head>
		<title>Google</title>
		<meta charset="UTF-8">
		<link rel="icon" href="/favicon.ico">
	</head>
</html>
END;

$meta = Crawler::init(html: $html, url: 'https://google.com')->run();

$icon = $meta->favicon->icons[0];

echo $icon->url // https://google.com/favicon.ico
```

> Pass the `url` parameter to convert relative URLs to absolute URLs.

Under the hood, the [GuzzleHttp](https://docs.guzzlephp.org/en/stable/) library is used to get html, so you can create your own request object and pass it as a `$request` parameter:

```php
$request = new \GuzzleHttp\Psr7\Request('GET', 'https://google.com');

$meta = Crawler::init(request: $request)->run();
```

All properties of the `meta` object describes [**here**](/docs/meta-object-properties.md).

## Configuration
<a name="config"></a>

```php
$crawler = Crawler::init(url: 'https://google.com');
$crawler->config
	->dontProcessUrls()
	->dontUseTypeConversions()
	->processUrlsWith('https://yandex.ru')
	->dontUseDefaultDistributorsConfiguration();
```

| Setting | Description |
|---------|-------------|
| ```dontProcessUrls()``` | Disable the conversion of relative URLs to absolute URLs. |
| ```dontUseTypeConversions()``` | Disable conversions string to int: <br><br> ```<meta property="og:image:height" content="630">``` <br> Using type conversions: ```int(630)``` <br> Disabled type conversions: ```string(3) "630"``` <br><br> ```<meta property="og:image:height" content="630.5">``` <br> Using type conversions: `null` <br> Disabled type conversions: ```string(5) "630.5"``` |
| ```processUrlsWith(string $url)``` | Sets the base URL for converting relative paths to absolute paths.<br> *Automatically enables URL processing and cancels the ```dontProcessUrls``` setting*. |
| ```dontUseDefaultDistributorsConfiguration()``` | Cancels the default configuration of the distributors. |

## Core concepts

Interaction with the library takes place through the main object `$crawler` of the type `\Osmuhin\HtmlMeta\Crawler`. From the moment of initialization to the call of the `run()` method, the configuration of the work takes place. <br>

What happens after calling the `run()` method:

* HTML string is requested at the specified URL (if HTML was not installed initially). <br>
The priority of the parameters, if they are more than 1: `string $html` ➡ `\GuzzleHttp\Psr7\Request $request` ➡ `string $url`;

* The HTML string begins to be parsed according to the `xpath` property:

	```php
	$crawler->xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';
	```

	You are free to overwrite xpath property;

* the found HTML element is pass to the distributor stack. <br>
If the HTML element passes the conditions, then its value is written to [DTO (Data Transfer Object)](https://en.wikipedia.org/wiki/Data_transfer_object ) of the type `\Osmuhin\HtmlMeta\Contracts\Dto`;

* after parsing the HTML string, the root DTO `\Osmuhin\HtmlMeta\Dto\Meta` is formed in output.

### Distributors

A **Distributor** is an object that validates html elements and distributes data over DTOs.

Distributor must implements the interface `\Osmuhin\HtmlMeta\Contracts\Distributor` and has 2 main methods:

```php
public function canHandle(\Osmuhin\HtmlMeta\Element $el): bool
{

}

public function handle(\Osmuhin\HtmlMeta\Element $el): void
{

}
```

```canHandle()``` - Checks whether the distributor can handle the current element.
If returns true, then all sub-distributors are polled, and then the handle method is called.

```handle()``` - Distributes the HTML element data by DTOs according to its own rules.

You can view the structure of the simplest [TitleDistributor](/src/Distributors/TitleDistributor.php) distributor:

```php
use Osmuhin\HtmlMeta\Element;

class TitleDistributor extends \Osmuhin\HtmlMeta\Distributors\AbstractDistributor
{
	public function canHandle(Element $el): bool
	{
		return $el->name === 'title';
	}

	public function handle(Element $el): void
	{
		$this->meta->title = $el->innerText;
	}
}
```

You are free to replace some kind distributor your own, example:

```php
use Osmuhin\HtmlMeta\Element;
use Osmuhin\HtmlMeta\Distributors\TitleDistributor;

class MyCustomTitleDistributor extends TitleDistributor
{
	public function handle(Element $el): void
	{
		$this->meta->title = 'Prefix for title ' . $el->innerText;
	}
}

$crawler = Crawler::init(url: 'https://google.com');
$crawler->distributor->setSubDistributor(
	MyCustomTitleDistributor::class,
	TitleDistributor::class
);

$meta = $crawler->run();
$meta->title === 'Prefix for title Google';
```

... or even completely overwrite the distributors tree:

```php
$crawler = Crawler::init(url: 'https://google.com');
$crawler->xpath = '//html/head/title';
$crawler->config->dontUseDefaultDistributorsConfiguration();

$crawler->distributor->useSubDistributors(
	MyCustomTitleDistributor::init($crawler->container)
);

$meta = $crawler->run();
```

<details>
<summary>Default distributors configuration</summary>

```php
$crawler->distributor->useSubDistributors(
	\Osmuhin\HtmlMeta\Distributors\HtmlDistributor::init(),
	\Osmuhin\HtmlMeta\Distributors\TitleDistributor::init(),
	\Osmuhin\HtmlMeta\Distributors\MetaDistributor::init()->useSubDistributors(
		\Osmuhin\HtmlMeta\Distributors\HttpEquivDistributor::init(),
		\Osmuhin\HtmlMeta\Distributors\TwitterDistributor::init(),
		\Osmuhin\HtmlMeta\Distributors\OpenGraphDistributor::init()
	),
	\Osmuhin\HtmlMeta\Distributors\LinkDistributor::init()->useSubDistributors(
		\Osmuhin\HtmlMeta\Distributors\FaviconDistributor::init()
	)
);
```
</details>

## Contributing

Thank you for considering to contribute. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).

You can contact me or just come say hi in Telegram: [@wischerdson](https://t.me/wischerdson)

## License

"HTML meta" package is an open-sourced software licensed under the [MIT license](LICENSE.md).
