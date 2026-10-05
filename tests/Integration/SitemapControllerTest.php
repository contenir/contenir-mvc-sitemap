<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap\Tests\Integration;

use Contenir\Mvc\Sitemap\Factory\SitemapControllerFactory;
use Contenir\Mvc\Sitemap\SitemapController;
use Contenir\Mvc\Sitemap\Tests\Trait\SitemapApplicationTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SitemapController::class)]
#[CoversClass(SitemapControllerFactory::class)]
#[Group('integration')]
final class SitemapControllerTest extends TestCase
{
    use SitemapApplicationTrait;

    #[Test]
    public function servesRobotsRulesPointingAtTheSitemap(): void
    {
        $response = $this->dispatch('/robots.txt');

        static::assertSame(
            [
                'text/plain; charset=utf-8',
                "# robots.txt for https://shop.example.org/\n\nUser-agent: *\nAllow: /\nDisallow: /.well-known/\n\n"
                    . 'Sitemap: https://shop.example.org/sitemap.xml',
            ],
            [$response->getHeaders()->get('Content-Type')?->getFieldValue(), $response->getContent()],
        );
    }

    #[Test]
    public function servesTheCmsNavigationAsASitemap(): void
    {
        $response = $this->dispatch('/sitemap.xml');

        static::assertSame(
            [
                'application/xml; charset=utf-8',
                '<?xml version="1.0" encoding="UTF-8"?>'
                    . "\n"
                    . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
                    . '<url><loc>https://www.example.com/</loc></url>'
                    . '<url><loc>https://www.example.com/about</loc></url>'
                    . '</urlset>',
            ],
            [$response->getHeaders()->get('Content-Type')?->getFieldValue(), $response->getContent()],
        );
    }

    private function dispatch(string $path): Response
    {
        $request = new Request();
        $request->setUri("https://shop.example.org{$path}");

        $router     = $this->createRouter();
        $routeMatch = $router->match($request);
        static::assertInstanceOf(RouteMatch::class, $routeMatch);

        $controller = (new SitemapControllerFactory())($this->createServices());
        $event      = new MvcEvent();
        $event->setRouter($router)->setRouteMatch($routeMatch);
        $controller->setEvent($event);

        $response = $controller->dispatch($request, new Response());
        static::assertInstanceOf(Response::class, $response);

        return $response;
    }
}
