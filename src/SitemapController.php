<?php

namespace Contenir\Mvc\Sitemap;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Helper\Navigation\Sitemap;

class SitemapController extends AbstractActionController
{
    private $sitemapHelper;

    public function __construct(Sitemap $sitemapHelper)
    {
        $this->sitemapHelper = $sitemapHelper;
    }

    public function indexAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        // Set HTTP header for XML content type
        $response->setHeaders(
            $response->getHeaders()->addHeaderLine(
                'Content-Type',
                'application/xml; charset=utf-8'
            )
        );

        // Render sitemap and set as content
        $response->setContent(
            $this->sitemapHelper->setContainer('cms')->render()
        );

        // Return HTTP response
        return $response;
    }

    public function robotsAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        // Set HTTP header for text/plain content type
        $response->setHeaders(
            $response->getHeaders()->addHeaderLine(
                'Content-Type',
                'text/plain; charset=utf-8'
            )
        );

        $scheme = $this->getRequest()->getUri()->getScheme();
        $host = $this->getRequest()->getUri()->getHost();
        $home = $this->url()->fromRoute('home');
        $sitemap = $this->url()->fromRoute('sitemap');

        $response->setContent(<<<ROBOTS
# robots.txt for {$scheme}://{$host}{$home}

User-agent: *
Allow: /
Disallow: /.well-known/

Sitemap: {$scheme}://{$host}{$sitemap}
ROBOTS);

        // Return HTTP response
        return $response;
    }
}
