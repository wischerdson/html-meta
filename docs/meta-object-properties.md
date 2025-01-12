# Properties of output meta object

## Root object

<!-- ----- -->

### appleItunesApp <small>`string` `null`</small>

<!-- ### appleItunesApp `string` `null` -->

<details>
<summary>Example</summary>

```html
<meta name="apple-itunes-app" content="app-id=myAppStoreID, app-argument=myURL">
```

```php
$meta->appleItunesApp === 'app-id=myAppStoreID, app-argument=myURL';
```
</details>

<!-- ----- -->

### appleMobileWebAppCapable <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="apple-mobile-web-app-capable" content="yes">
```

```php
$meta->appleMobileWebAppCapable === 'yes';
```
</details>

<!-- ----- -->

### appleMobileWebAppStatusBarStyle <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="apple-mobile-web-app-status-bar-style" content="black">
```

```php
$meta->appleMobileWebAppStatusBarStyle === 'black';
```
</details>


### applicationName <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="application-name" content="Amazon">
```

```php
$meta->applicationName === 'Amazon';
```
</details>

<!-- ----- -->

### author <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="author" content="Osmuhin Daniil">
```

```php
$meta->author === 'Osmuhin Daniil';
```
</details>

<!-- ----- -->

### charset <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta charset="utf-8">
```

```php
$meta->charset === 'utf-8';
```
</details>

<!-- ----- -->

### colorScheme <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="color-scheme" content="light dark">
```

```php
$meta->colorScheme === 'light dark';
```
</details>

<!-- ----- -->

### copyright <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="copyright" content="Apple Inc.">
```

```php
$meta->copyright === 'Apple Inc.';
```
</details>

<!-- ----- -->

### description <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="description" content="Some description">
```

```php
$meta->description === 'Some description.';
```
</details>

<!-- ----- -->

### dir <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<html dir="ltr"></html>
```

```php
$meta->dir === 'ltr';
```
</details>

<!-- ----- -->

### favicon [<small>`Favicon`</small>](#favicon-object)

<details>
<summary>Example</summary>

```html
<html dir="ltr"></html>
```

```php
$meta->dir === 'ltr';
```
</details>

<!-- ----- -->

### formatDetection <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="format-detection" content="telephone=no">
```

```php
$meta->formatDetection === 'telephone=no';
```
</details>

<!-- ----- -->

### generator <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="generator" content="WordPress.com">
```

```php
$meta->generator === 'telephone=no';
```
</details>

<!-- ----- -->

### htmlAttributes <small>`array<string>`</small>

<details>
<summary>Example</summary>

```html
<html lang="en" data-theme="dark" dir="rtl"></html>
```

```php
$meta->htmlAttributes === [
	'lang' => 'en',
	'data-theme' => 'dark',
	'dir' => 'rtl'
];
```
</details>

<!-- ----- -->

### httpEquiv [<small>`HttpEquiv`</small>](#httpequiv-object)

<details>
<summary>Example</summary>

```html
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="Tue, January 01, 2025, 12:00:00 GMT">
<meta http-equiv="refresh" content="5; url=https://example.com">
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">
<meta http-equiv="x-dns-prefetch-control" content="on">
<meta http-equiv="Access-Control-Allow-Origin" content="*">
<meta http-equiv="non-standart-meta" content="some-value">
```

```php
$meta->httpEquiv->toArray() === [
	'contentType' => 'text/html; charset=UTF-8',
	'xUaCompatible' => 'IE=edge',
	'cacheControl' => 'no-cache',
	'contentLanguage' => 'en',
	'pragma' => 'no-cache',
	'expires' => 'Tue, January 01, 2025, 12:00:00 GMT',
	'refresh' => '5; url=https://example.com',
	'contentSecurityPolicy' => "default-src 'self';",
	'xDnsPrefetchControl' => 'on',
	'accessControlAllowOrigin' => '*',
	'non-standart-meta' => 'some-value'
];
```
</details>

<!-- ----- -->

### keywords <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="keywords" content="money, exchange">
```

```php
$meta->keywords === 'money, exchange';
```
</details>

<!-- ----- -->

### lang <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<html lang="en_US"></html>
```

```php
$meta->lang === 'en_US';
```
</details>

<!-- ----- -->

### openGraph [<small>`OpenGraph`</small>](#opengraph-object)

<details>
<summary>Example</summary>

```html
<html lang="en_US"></html>
```

```php
$meta->lang === 'en_US';
```
</details>

<!-- ----- -->

### referrer <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="referrer" content="origin">
```

```php
$meta->referrer === 'origin';
```
</details>

<!-- ----- -->

### robots <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="robots" content="noindex">
```

```php
$meta->robots === 'noindex';
```
</details>

<!-- ----- -->

### themeColor <small>`array<string>`</small>

<details>
<summary>Example</summary>

```html
<meta name="theme-color" content="#eb0c0c">
<meta name="theme-color" media="(prefers-color-scheme: light)" content="#fff">
```

```php
$meta->themeColor === [
	0 => '#eb0c0c',
	'(prefers-color-scheme: light)' => '#fff'
];
```
</details>

<!-- ----- -->

### title <small>`string` `null`</small>

> [!NOTE]
> The title can be set in two ways: via the `title` tag or via the `meta` tag. Priority is given to the `title` tag if both variants are present in the html document.

<details>
<summary>Example</summary>

```html
<title>Google</title>
<meta name="title" content="DuckDuckGo">
```

```php
$meta->title === 'Google';
```
</details>

<!-- ----- -->

### twitter [<small>`Twitter`</small>](#twitter-object)

<details>
<summary>Example</summary>

```html
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@username">
<meta name="twitter:title" content="Laravel - The PHP Framework For Web Artisans">
<meta name="twitter:description" content="Some description for twitter card.">
<meta name="twitter:image" content="/image.jpg">
<meta name="twitter:image:alt" content="A description of the image">
<meta name="twitter:creator" content="@author">
<meta name="twitter:app:id:iphone" content="123456789">
```

```php
$meta->twitter->toArray() === [
	'card' => 'summary',
	'site' => '@username',
	'title' => 'Laravel - The PHP Framework For Web Artisans',
	'description' => 'Some description for twitter card.',
	'image' => 'https://example.com/image.jpg',
	'imageAlt' => 'A description of the image',
	'creator' => '@author',
	'other' => ['twitter:app:id:iphone' => '123456789'],
];
```
</details>

<!-- ----- -->

### viewport <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="viewport" content="width=device-width, initial-scale=1">
```

```php
$meta->viewport === 'width=device-width, initial-scale=1';
```
</details>

<!-- ----- -->

### unrecognizedMeta <small>`array<string>`</small>

<details>
<summary>Example</summary>

```html
<meta property="fb:app_id" content="123456789012345">
```

```php
$meta->unrecognizedMeta === ['fb:app_id' => '123456789012345'];
```
</details>

<!-- ----- -->

## Favicon object
<a name="favicon-object"></a>


### manifest <small>`string` `null`</small>

> [!NOTE]
> The property process as a URL if not disabled via the `dontProcessUrls` setting.

<details>
<summary>Example</summary>

```html
<link rel="manifest" href="/manifest.webmanifest">
```

```php
$meta->favicon->manifest === 'https://example.com/manifest.webmanifest';
```
</details>

<!-- ----- -->

### icons <small><code>array<<a href="#icon-object">Icon</a>></code></small>


<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon-small.ico" sizes="16X14">
```

```php
$meta->favicon->icons[0]->toArray() === [
	'url' => 'http://example.com/path/favicon-small.ico',
	'mime' => 'application/ico',
	'extension' => 'ico',
	'width' => 16,
	'height' => 14,
	'sizes' => '16x14'
];
```
</details>

<!-- ----- -->

### appleTouchIcons <small><code>array<<a href="#icon-object">Icon</a>></code></small>

<details>
<summary>Example</summary>

```html
<link rel="apple-touch-icon" href="apple.com/apple-touch-icon.png">
```

```php
$meta->favicon->appleTouchIcons[0]->toArray() === [
	'url' => 'apple.com/apple-touch-icon.png',
	'mime' => 'image/png',
	'extension' => 'png',
	'width' => null,
	'height' => null,
	'sizes' => null
];
```
</details>

<!-- ----- -->


## HttpEquiv object
<a name="httpequiv-object"></a>

### contentType <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
```

```php
$meta->httpEquiv->contentType === 'text/html; charset=UTF-8';
```
</details>

<!-- ----- -->

### xUaCompatible <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="X-UA-Compatible" content="IE=edge">
```

```php
$meta->httpEquiv->xUaCompatible === 'IE=edge';
```
</details>

<!-- ----- -->

### cacheControl <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="Cache-Control" content="no-cache">
```

```php
$meta->httpEquiv->cacheControl === 'no-cache';
```
</details>

<!-- ----- -->

### contentLanguage <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="Content-Language" content="en">
```

```php
$meta->httpEquiv->contentLanguage === 'en';
```
</details>

<!-- ----- -->

### pragma <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="pragma" content="no-cache">
```

```php
$meta->httpEquiv->pragma === 'no-cache';
```
</details>

<!-- ----- -->

### expires <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="expires" content="Tue, January 01, 2025, 12:00:00 GMT">
```

```php
$meta->httpEquiv->expires === 'Tue, January 01, 2025, 12:00:00 GMT';
```
</details>

<!-- ----- -->

### refresh <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="refresh" content="5; url=https://example.com">
```

```php
$meta->httpEquiv->expires === '5; url=https://example.com';
```
</details>

<!-- ----- -->

### contentSecurityPolicy <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="Content-Security-Policy" content="default-src 'self';">
```

```php
$meta->httpEquiv->contentSecurityPolicy === "default-src 'self';";
```
</details>

<!-- ----- -->

### xDnsPrefetchControl <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="x-dns-prefetch-control" content="on">
```

```php
$meta->httpEquiv->xDnsPrefetchControl === 'on';
```
</details>

<!-- ----- -->

### accessControlAllowOrigin <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="Access-Control-Allow-Origin" content="*">
```

```php
$meta->httpEquiv->accessControlAllowOrigin === '*';
```
</details>

<!-- ----- -->

### other <small>`array<string>`</small>

<details>
<summary>Example</summary>

```html
<meta http-equiv="non-standart-meta" content="some-value">
```

```php
$meta->httpEquiv->other === ['non-standart-meta' => 'some-value'];
```
</details>

<!-- ----- -->

## OpenGraph object
<a name="opengraph-object"></a>

## Twitter object
<a name="twitter-object"></a>

### card <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:card" content="summary">
```

```php
$meta->twitter->card === 'summary';
```
</details>

<!-- ----- -->

### site <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:site" content="@username">
```

```php
$meta->twitter->site === '@username';
```
</details>

<!-- ----- -->

### title <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:title" content="Laravel - The PHP Framework For Web Artisans">
```

```php
$meta->twitter->title === 'Laravel - The PHP Framework For Web Artisans';
```
</details>

<!-- ----- -->

### description <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:description" content="Laravel is a PHP web application framework with expressive, elegant syntax.">
```

```php
$meta->twitter->description === 'Laravel is a PHP web application framework with expressive, elegant syntax.';
```
</details>

<!-- ----- -->

### image <small>`string` `null`</small>

> [!NOTE]
> The property process as a URL if not disabled via the `dontProcessUrls` setting.

<details>
<summary>Example</summary>

```html
<meta name="twitter:image" content="/image.jpg">
```

```php
$meta->twitter->image === 'https://example.com/image.jpg';
```
</details>

<!-- ----- -->

### imageAlt <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:image:alt" content="A description of the image">
```

```php
$meta->twitter->imageAlt === 'A description of the image';
```
</details>

<!-- ----- -->

### creator <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:creator" content="@author">
```

```php
$meta->twitter->creator === '@author';
```
</details>

<!-- ----- -->

### other <small>`array<string>`</small>

<details>
<summary>Example</summary>

```html
<meta name="twitter:app:id:iphone" content="123456789">
```

```php
$meta->twitter->other === ['twitter:app:id:iphone' => '123456789'];
```
</details>

## Icon object
<a name="icon-object"></a>


### url <small>`string`</small>

> [!NOTE]
> The property process as a URL if not disabled via the `dontProcessUrls` setting.

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->url === 'https://example.com/favicon.ico';
```
</details>

<!-- ----- -->

### mime <small>`string` `null`</small>

> [!NOTE]
> The mime type will be set automatically based on the file extension if the "type" attribute is not specified.

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->mime === 'application/ico';
```
</details>

<!-- ----- -->

### extension <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->extension === 'ico';
```
</details>

<!-- ----- -->

### sizes <small>`string` `null`</small>

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->sizes === '16x14';
```
</details>

<!-- ----- -->

### width <small>`int` `string` `null`</small>

The property is computed from the "sizes" attribute (First number before "**x**").

> [!NOTE]
> The property will try to converse the value to `int`. In case of an incorrect value, `null` will be written. You can disable this behavior with the `dontUseTypeConversions` function (See [configuration](/README.md#config)).

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->width === 16;
```
</details>

<!-- ----- -->

### height <small>`int` `string` `null`</small>

The property is computed from the "sizes" attribute (Last number after "**x**").

> [!NOTE]
> The property will try to converse the value to `int`. In case of an incorrect value, `null` will be written. You can disable this behavior with the `dontUseTypeConversions` function (See [configuration](/README.md#config)).

<details>
<summary>Example</summary>

```html
<link rel="shortcut icon" href="favicon.ico" sizes="16x14">
```

```php
$icon->height === 14;
```
</details>

<!-- ----- -->
