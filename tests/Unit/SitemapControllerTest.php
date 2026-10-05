<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap\Tests\Unit;

use Contenir\Mvc\Sitemap\SitemapController;
use Laminas\Http\Request as HttpRequest;
use Laminas\Http\Response as HttpResponse;
use Laminas\Mvc\Exception\DomainException;
use Laminas\Router\RouteMatch;
use Laminas\Stdlib\Request;
use Laminas\Stdlib\RequestInterface;
use Laminas\Stdlib\Response;
use Laminas\Stdlib\ResponseInterface;
use Laminas\View\Helper\Navigation\Sitemap;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SitemapController::class)]
#[Group('unit')]
final class SitemapControllerTest extends TestCase
{
    /**
     * @return array<string, array{string, RequestInterface, ResponseInterface, string}>
     */
    public static function nonHttpProvider(): array
    {
        return [
            'sitemap without an HTTP response' => [
                'index',
                new HttpRequest(),
                new Response(),
                'The sitemap controller needs an HTTP response',
            ],
            'robots without an HTTP response'  => [
                'robots',
                new HttpRequest(),
                new Response(),
                'The sitemap controller needs an HTTP response',
            ],
            'robots without an HTTP request'   => [
                'robots',
                new Request(),
                new HttpResponse(),
                'The robots action needs an HTTP request',
            ],
        ];
    }

    #[Test]
    #[DataProvider('nonHttpProvider')]
    public function refusesToServeOutsideHttp(
        string $action,
        RequestInterface $request,
        ResponseInterface $response,
        string $message,
    ): void {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage($message);

        $this->dispatch(new SitemapController($this->createStub(Sitemap::class)), $action, $request, $response);
    }

    #[Test]
    public function rendersTheCmsContainerWithTheSitemapHelper(): void
    {
        $sitemap = $this->createMock(Sitemap::class);
        $sitemap->expects(static::once())->method('setContainer')->with('cms')->willReturnSelf();
        $sitemap->method('render')->willReturn('<urlset/>');

        $response = $this->dispatch(new SitemapController($sitemap), 'index', new HttpRequest(), new HttpResponse());

        static::assertSame('<urlset/>', $response->getContent());
    }

    #[Test]
    public function robotsActionNeedsARouterToLinkTheSitemap(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Url plugin requires that controller event compose a router; none found');

        (new SitemapController($this->createStub(Sitemap::class)))->robotsAction();
    }

    private function dispatch(
        SitemapController $controller,
        string $action,
        RequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $controller->getEvent()->setRouteMatch(new RouteMatch(['action' => $action]));

        $result = $controller->dispatch($request, $response);
        static::assertInstanceOf(ResponseInterface::class, $result);

        return $result;
    }
}
