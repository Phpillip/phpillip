<?php

namespace Phpillip\Console\EventListener;

use DateTime;
use Phpillip\Console\Model\Sitemap;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Map all routes into a Sitemap
 */
class SitemapListener implements EventSubscriberInterface
{
    /**
     * Routes
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Sitemap
     *
     * @var Sitemap
     */
    protected $sitemap;

    /**
     * Constructor
     *
     * @param RouteCollection $routes
     * @param Sitemap $sitemap
     */
    public function __construct(RouteCollection $routes, Sitemap $sitemap)
    {
        $this->routes  = $routes;
        $this->sitemap = $sitemap;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelReponse',
        ];
    }

    /**
     * Handler Kernel Response events
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelReponse(FilterResponseEvent $event)
    {
        $request  = $event->getRequest();
        $response = $event->getResponse();
        $route    = $this->routes->get($request->attributes->get('_route'));

        if ($route && $route->isMapped()) {
            $url          = $request->attributes->get('_canonical');
            $lastModified = new DateTime($response->headers->get('Last-Modified'));

            $this->sitemap->add($url, $lastModified);
        }
    }
}
