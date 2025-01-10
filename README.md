<p align="center">
	<img src="https://raw.githubusercontent.com/wischerdson/html-meta/refs/heads/master/logo.svg" alt="HTML meta logo" width="320">
</p>

<p align="center">
	<a href="https://github.com/wischerdson/html-meta/actions">
		<img src="https://github.com/wischerdson/html-meta/actions/workflows/tests.yml/badge.svg" alt="Tests status">
	</a>
<!-- <a href="https://packagist.org/packages/laravel/framework">
<img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a> -->
<!-- <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a> -->
	<a href="https://packagist.org/packages/osmuhin/html-meta">
		<img src="https://badgen.net/github/license/wischerdson/html-meta" alt="License">
	</a>
</p>

---

## Installation

You can install the package via composer:

```bash
composer require osmuhin/html-meta
```

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

Under the hood, the [GuzzleHttp](https://docs.guzzlephp.org/en/stable/) library is used to get html, so you can create your own request object and pass it as a request parameter:

```php
$request = new \GuzzleHttp\Psr7\Request('GET', 'https://google.com');

$meta = Crawler::init(request: $request)->run();
```

## Configuration

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
| ```dontUseTypeConversions()``` | Disable conversions string to int: <br><br> ```<meta property="og:image:height" content="630">``` <br> Using type conversions: ```int(630)``` <br> Disabled type conversions: ```string(3) "630"``` <br><br> ```<meta property="og:image:height" content="630.5">``` <br> Using type conversions: ```NULL``` <br> Disabled type conversions: ```string(5) "630.5"``` |
| ```processUrlsWith(string $url)``` | Sets the base URL for converting relative paths to absolute paths.<br> *Automatically enables URL processing and cancels the ```dontProcessUrls``` setting*. |
| ```dontUseDefaultDistributorsConfiguration()``` | Cancels the default configuration of the distributers. <br> |
|

## Core concepts
### Operating procedure

Взаимодействие с библиотекой происходит через основной объект ```$crawler``` типа ```\Osmuhin\HtmlMeta\Crawler```. С момента инициализации и до вызова метода ```run()``` происходит конфигурация работы. <br>

После вызова метода ```run()```:

* запрашивается HTML по указанному URL (если HTML не был установлен изначально). <br>
Приоритет параметров, если их больше 1: ```string $html``` ➡ ```\GuzzleHttp\Psr7\Request $request``` ➡ ```string $url```;

* HTML строка начинает разбираться по элементам в соответствии со свойством ```xpath```:<br>
	```php
	$crawler->xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';
	```


* найденный HTML-элемент отдается в стек дитрибьютеров;

* если HTML-элемент проходит условия, то его значение записывается в [DTO (Data Transfer Object)](https://en.wikipedia.org/wiki/Data_transfer_object) типа ```\Osmuhin\HtmlMeta\Contracts\Dto```;

* после окончания парсинга HTML-строки формируется DTO ```\Osmuhin\HtmlMeta\Dto\Meta```.

### Distributors

```php
// Default configuration:
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

---
## Custom initializing:
```php

$crawler = new Crawler();

$crawler->setHtml(string $html);
$crawler->setUrl(string $url);
$crawler->setRequest(\GuzzleHttp\Psr7\Request $request);
$crawler->setGuzzleClient(\GuzzleHttp\Client $guzzleClient);

$crawler->xpath = '//html|//html/head/link|//html/head/meta|//html/head/title';

$crawler->

setGuzzleClient
```


## Contributing

Thank you for considering to contribute. All the contribution guidelines are mentioned [here](CONTRIBUTING.md).

You can contact me or just come say hi in Telegram: [@wischerdson](https://t.me/wischerdson)

## License

"HTML meta" package is an open-sourced software licensed under the [MIT license](LICENSE.md).
