<?php

namespace Contenir\Mvc\Sitemap\Factory;

use Contenir\Mvc\Sitemap\SitemapController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Helper\Navigation as NavigationProxyHelper;
use Laminas\View\Helper\Navigation\Sitemap;
use Laminas\View\HelperPluginManager;

class SitemapControllerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
    ): SitemapController {
        // View helper manager
        /** @var HelperPluginManager $viewHelperPluginManager */
        $viewHelperPluginManager = $container->get('ViewHelperManager');

        // Navigation view helper
        /** @var NavigationProxyHelper $navigationHelper */
        $navigationHelper = $viewHelperPluginManager->get(
            NavigationProxyHelper::class
        );

        // Sitemap view helper
        /** @var Sitemap $sitemapHelper */
        $sitemapHelper = $navigationHelper->findHelper(Sitemap::class);

        $controller = new SitemapController($sitemapHelper);

        return $controller;
    }
}
