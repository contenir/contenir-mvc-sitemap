<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap\Tests\Integration\Factory;

use Contenir\Mvc\Sitemap\Factory\SitemapControllerFactory;
use Contenir\Mvc\Sitemap\Tests\Trait\SitemapApplicationTrait;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\View\Helper\Navigation as NavigationHelper;
use Laminas\View\Helper\Navigation\Menu;
use Laminas\View\Helper\Navigation\Sitemap;
use Laminas\View\HelperPluginManager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SitemapControllerFactory::class)]
#[Group('integration')]
final class SitemapControllerFactoryTest extends TestCase
{
    use SitemapApplicationTrait;

    #[Test]
    public function rejectsANavigationPluginThatIsNotASitemapHelper(): void
    {
        $services = $this->createServices();
        $helpers  = $services->get('ViewHelperManager');
        static::assertInstanceOf(HelperPluginManager::class, $helpers);

        $plugins = $helpers->get(NavigationHelper::class)->getPluginManager();
        $plugins->setAllowOverride(true);
        $plugins->setService(Sitemap::class, new Menu());

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            'Navigation helper "Laminas\View\Helper\Navigation\Sitemap" must be a '
                . 'Laminas\View\Helper\Navigation\Sitemap, Laminas\View\Helper\Navigation\Menu given',
        );

        (new SitemapControllerFactory())($services);
    }
}
