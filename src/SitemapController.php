<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap;

use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Exception\DomainException;
use Laminas\Mvc\Exception\RuntimeException as MvcRuntimeException;
use Laminas\View\Exception\ExceptionInterface as ViewException;
use Laminas\View\Helper\Navigation\Sitemap;
use Override;

use function sprintf;

/**
 * Serves /sitemap.xml from the "cms" navigation container, and a
 * /robots.txt that allows everything except /.well-known/ and points at
 * the sitemap.
 *
 * @api
 */
final class SitemapController extends AbstractActionController
{
    /**
     * The navigation container service the sitemap is rendered from.
     */
    public const string CONTAINER = 'cms';

    public function __construct(
        private readonly Sitemap $sitemapHelper,
    ) {}

    /**
     * @return Response The rendered sitemap XML.
     *
     * @throws DomainException when not dispatched over HTTP.
     * @throws ViewException when the navigation container cannot be found or rendered.
     *
     * @mago-expect analysis:incompatible-return-type laminas-mvc returns any action result as is; the parent's
     *                                                ViewModel return type is docblock-only.
     */
    #[Override]
    public function indexAction(): Response
    {
        $response = $this->httpResponse('application/xml; charset=utf-8');
        $response->setContent($this->sitemapHelper->setContainer(self::CONTAINER)->render());

        return $response;
    }

    /**
     * @throws DomainException when not dispatched over HTTP.
     * @throws MvcRuntimeException when the "home" or "sitemap" route is missing.
     */
    public function robotsAction(): Response
    {
        $response = $this->httpResponse('text/plain; charset=utf-8');
        $request  = $this->getRequest();
        if (! $request instanceof Request) {
            throw new DomainException('The robots action needs an HTTP request');
        }

        $origin  = sprintf('%s://%s', (string) $request->getUri()->getScheme(), (string) $request->getUri()->getHost());
        $home    = $this->url()->fromRoute('home');
        $sitemap = $this->url()->fromRoute('sitemap');

        $response->setContent(<<<ROBOTS
            # robots.txt for {$origin}{$home}

            User-agent: *
            Allow: /
            Disallow: /.well-known/

            Sitemap: {$origin}{$sitemap}
            ROBOTS);

        return $response;
    }

    /**
     * @throws DomainException when not dispatched over HTTP.
     */
    private function httpResponse(string $contentType): Response
    {
        $response = $this->getResponse();
        if (! $response instanceof Response) {
            throw new DomainException('The sitemap controller needs an HTTP response');
        }

        $response->getHeaders()->addHeaderLine('Content-Type', $contentType);

        return $response;
    }
}
