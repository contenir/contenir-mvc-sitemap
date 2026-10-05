<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap\Tests\Unit\Factory;

use Contenir\Mvc\Sitemap\Factory\SitemapControllerFactory;
use Contenir\Mvc\Sitemap\Tests\TestAsset\Container\InMemoryContainer;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(SitemapControllerFactory::class)]
#[Group('unit')]
final class SitemapControllerFactoryTest extends TestCase
{
    #[Test]
    public function rejectsAViewHelperManagerOfTheWrongType(): void
    {
        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            'Service "ViewHelperManager" must be a Laminas\View\HelperPluginManager, stdClass given',
        );

        (new SitemapControllerFactory())(new InMemoryContainer(['ViewHelperManager' => new stdClass()]));
    }
}
