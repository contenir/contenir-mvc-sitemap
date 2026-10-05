# Upgrading from 1.x to 2.0

2.0 serves the same routes with the same output. Only the platform and
code that extends or calls the module's classes directly are affected.

| | 1.x | 2.0 |
| --- | --- | --- |
| PHP | ^8.0 | 8.3, 8.4 or 8.5 |
| laminas-mvc | ^3.0 | ^3.7 |
| laminas-servicemanager | (undeclared) | ^3.22 or ^4.0 |
| laminas-navigation | (undeclared) | ^2.19 |

```bash
composer require contenir/contenir-mvc-sitemap:^2.0
```

Projects that must stay on PHP 8.0 to 8.2 can keep using `^1.0`, maintained
on the `1.x` branch.

## `SitemapControllerFactory`

The factory is `final`, takes a PSR-11 container and no longer implements
`FactoryInterface`. laminas-servicemanager calls it unchanged, but code that
calls it directly, or extends it, needs updating:

```php
// 1.x
class MyFactory extends SitemapControllerFactory { /* … */ }
$controller = (new SitemapControllerFactory())($container, SitemapController::class, null);

// 2.0: compose instead of extending; the extra arguments are ignored
$controller = (new SitemapControllerFactory())($container);
```

## `Module`

`Module` is `final`, and `getConfig()` declares an `array` return type.
Override its routes in your application's configuration instead of
extending it:

```php
// config/autoload/sitemap.global.php
return [
    'router' => ['routes' => ['robots' => ['options' => ['route' => '/robots-legacy.txt']]]],
];
```

## `SitemapController`

The controller is unchanged for laminas-mvc applications. Dispatched without
an HTTP request or response, it now throws
`Laminas\Mvc\Exception\DomainException` instead of failing with an `Error`.
