# contenir/contenir-sitemap-laminas-mvc

Formerly `contenir/contenir-mvc-sitemap`; the old package is abandoned in favour of this one.

[![Continuous Integration](https://github.com/contenir/contenir-sitemap-laminas-mvc/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/contenir/contenir-sitemap-laminas-mvc/actions/workflows/continuous-integration.yml)
[![codecov](https://codecov.io/gh/contenir/contenir-sitemap-laminas-mvc/graph/badge.svg)](https://codecov.io/gh/contenir/contenir-sitemap-laminas-mvc)

A laminas-mvc module for [Contenir CMS](https://contenir.com.au) sites. It
serves `/sitemap.xml` from the site's `cms` navigation container, and a
`/robots.txt` that points crawlers at it.

## Requirements

- PHP 8.3, 8.4 or 8.5
- laminas-mvc 3.8+, laminas-router 3.13+, laminas-view 2.35+ and
  laminas-navigation 2.19+

The 1.x releases, which support PHP 8.0 to 8.2, remain available from the
`1.x` branch and `v1.*` tags; see [UPGRADE-2.0.md](UPGRADE-2.0.md).

## Installation

```bash
composer require contenir/contenir-sitemap-laminas-mvc
```

With the Laminas component installer, the `Contenir\Mvc\Sitemap` module
registers itself. Otherwise add it to `config/modules.config.php`.

## Usage

The module adds two routes, both served by `SitemapController`:

| Route | Path | Response |
| --- | --- | --- |
| `sitemap` | `/sitemap.xml` | `application/xml`: the `cms` navigation container, rendered by laminas-view's `navigation()->sitemap()` helper |
| `robots` | `/robots.txt` | `text/plain`, as below |

```text
# robots.txt for https://www.example.com/

User-agent: *
Allow: /
Disallow: /.well-known/

Sitemap: https://www.example.com/sitemap.xml
```

The scheme and host come from the request, and the paths from the `home`
and `sitemap` routes.

## Configuration

The application must provide:

- **A `cms` navigation container service** (`SitemapController::CONTAINER`),
  such as one built by laminas-navigation's abstract factory from
  `'navigation' => ['cms' => [...]]` configuration. Page URIs are made
  absolute with the `serverUrl()` helper.
- **A `home` route**, used in the robots.txt comment.
- **The navigation view helper**, which laminas-navigation's module
  registers in the `ViewHelperManager`.

Override either route in your own router configuration to move it.

## Development

The QA toolchain is [php-db/phpdb-qa-tools](https://github.com/php-db/phpdb-qa-tools).
[Mago](https://mago.carthage.software/) is a standalone binary, installed
separately (`brew install mago`).

```bash
composer check             # everything below
composer cs-check          # mago format --check && mago lint
composer static-analysis   # mago analyze
composer test              # unit suite: module config and controller with a doubled helper, no I/O
composer test-integration  # integration suite: real router, view helpers and navigation container
composer test-coverage     # both suites, clover.xml for Codecov
composer mutation-test     # Infection mutation testing over both suites
```

## License

BSD-3-Clause. See [LICENSE.md](LICENSE.md).
