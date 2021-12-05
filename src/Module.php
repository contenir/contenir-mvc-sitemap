<?php

/**
 * @see       https://github.com/contenir/contenir-mvc-sitemap for the canonical source repository
 * @copyright https://github.com/contenir/contenir-mvc-sitemap/blob/master/COPYRIGHT.md
 */

namespace Contenir\Mvc\Sitemap;

use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

class Module
{
    /**
     * Provide application configuration.
     *
     * Adds routes and factories for the Sitemap Controller.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'router' => [
                'routes' => [
                    'sitemap' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/sitemap.xml',
                            'defaults' => [
                                'controller' => SitemapController::class,
                                'action' => 'index'
                            ],
                        ],
                    ],
                    'robots' => [
                        'type' => Literal::class,
                        'options' => [
                            'route'    => '/robots.txt',
                            'defaults' => [
                                'controller' => SitemapController::class,
                                'action' => 'robots'
                            ],
                        ],
                    ],
                ],
            ],
            'controllers' => [
                'factories' => [
                    SitemapController::class => Factory\SitemapControllerFactory::class
                ]
            ]
        ];
    }
}
