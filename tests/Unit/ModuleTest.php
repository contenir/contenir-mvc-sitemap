<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap\Tests\Unit;

use Contenir\Mvc\Sitemap\Factory\SitemapControllerFactory;
use Contenir\Mvc\Sitemap\Module;
use Contenir\Mvc\Sitemap\SitemapController;
use Laminas\Router\Http\Literal;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Module::class)]
#[Group('unit')]
final class ModuleTest extends TestCase
{
    /**
     * @return array<string, array{string, string, string}>
     */
    public static function routeProvider(): array
    {
        return [
            'sitemap' => ['sitemap', '/sitemap.xml', 'index'],
            'robots'  => ['robots', '/robots.txt', 'robots'],
        ];
    }

    #[Test]
    public function registersTheControllerFactory(): void
    {
        static::assertSame(
            [SitemapController::class => SitemapControllerFactory::class],
            (new Module())->getConfig()['controllers']['factories'],
        );
    }

    #[Test]
    #[DataProvider('routeProvider')]
    public function routesToTheSitemapController(string $name, string $path, string $action): void
    {
        static::assertSame(
            [
                'type'    => Literal::class,
                'options' => [
                    'route'    => $path,
                    'defaults' => ['controller' => SitemapController::class, 'action' => $action],
                ],
            ],
            (new Module())->getConfig()['router']['routes'][$name] ?? null,
        );
    }
}
