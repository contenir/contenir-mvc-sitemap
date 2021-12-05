<?php

/**
 * @see       https://github.com/contenir/contenir-mvc-sitemap for the canonical source repository
 * @copyright https://github.com/contenir/contenir-mvc-sitemap/blob/master/COPYRIGHT.md
 */

namespace Contenir\Mvc\Controller;

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
            'routes' => [
                'sitemap' => [
                    'type' => Literal::class,
                    'options' => [
                        'route'    => '/sitemap.xml',
                        'defaults' => [
                            'controller' => Controller\SitemapController::class,
                            'action' => 'index'
                        ],
                    ],
                ],
                'robots' => [
                    'type' => Literal::class,
                    'options' => [
                        'route'    => '/robots.txt',
                        'defaults' => [
                            'controller' => Controller\SitemapController::class,
                            'action' => 'robots'
                        ],
                    ],
                ],
            ],
            'controllers' => [
                'factories' => [
                    Controller\SitemapController::class => Controller\Factory\SitemapController::class
                ]
            ]
        ];
    }
}
