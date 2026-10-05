<?php

declare(strict_types=1);

namespace Contenir\Mvc\Sitemap;

use Laminas\Router\Http\Literal;

/**
 * laminas-mvc module: the /sitemap.xml and /robots.txt routes and the
 * controller that serves them.
 *
 * @see https://github.com/contenir/contenir-sitemap-laminas-mvc for the canonical source repository
 *
 * @api
 */
final class Module
{
    /**
     * @return array{
     *     router: array{routes: array<string, array<string, mixed>>},
     *     controllers: array{factories: array<class-string, class-string>},
     * }
     */
    public function getConfig(): array
    {
        return [
            'router'      => [
                'routes' => [
                    'sitemap' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/sitemap.xml',
                            'defaults' => [
                                'controller' => SitemapController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'robots'  => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/robots.txt',
                            'defaults' => [
                                'controller' => SitemapController::class,
                                'action'     => 'robots',
                            ],
                        ],
                    ],
                ],
            ],
            'controllers' => [
                'factories' => [
                    SitemapController::class => Factory\SitemapControllerFactory::class,
                ],
            ],
        ];
    }
}
