# Changelog

All notable changes to this project are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/), and the project
adheres to [Semantic Versioning](https://semver.org/).

## [2.0.0] - Unreleased

The routes, the controller and their output are unchanged. The major
version marks the move to PHP 8.3+ and the php-db QA toolchain shared by
all Contenir 2.x packages. See [UPGRADE-2.0.md](UPGRADE-2.0.md).

### Changed

- Requires PHP 8.3, 8.4 or 8.5. PHP 8.0 to 8.2 are no longer supported.
- `SitemapControllerFactory` takes a PSR-11 container instead of the
  deprecated container-interop interface, which laminas-servicemanager 4
  drops, and no longer implements laminas-servicemanager's
  `FactoryInterface`. `Module` and the
  factory are `final`.
- The controller throws a `DomainException` when it is dispatched without an
  HTTP request or response.
- The laminas packages the code uses (laminas-http, laminas-router,
  laminas-view, laminas-navigation, laminas-servicemanager) and
  `psr/container` are now required. Before, only laminas-mvc was declared.

### Added

- `SitemapController::CONTAINER`, the navigation container's service name
  (`cms`).
- Continuous integration on PHP 8.3, 8.4 and 8.5 against lowest, locked and
  latest dependencies, with coverage reported to Codecov.
- Unit and integration test suites, with 100% line and branch coverage.

### Fixed

- A `ViewHelperManager` or Sitemap helper of the wrong type raises a
  `ServiceNotCreatedException` that names it, instead of a `TypeError`.

### Removed

- `laminas/laminas-coding-standard` and `phpcs.xml`, replaced by Mago via
  `php-db/phpdb-qa-tools`.

## [1.0.6] - 2023-12-15

- Drop PHP 7.x support.

## [1.0.0] to [1.0.5] - 2021-12-05

- Initial release: sitemap and robots.txt routes, controller and factory,
  then autoload and component installer fixes.
