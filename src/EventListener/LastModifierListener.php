<?php

namespace Phpillip\EventListener;

use Phpillip\Model\Paginator;
use Phpillip\Routing\Route;
use Phpillip\Service\ContentRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Add Last-Modified header to content routes response
 */
class LastModifierListener implements EventSubscriberInterface
{
    /**
     * Routes
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor
     *
     * @param RouteCollection $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
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

        if ($route && $route->hasContent() && !$response->headers->has('Last-Modified')) {
            $this->setLastModifiedHeader($route, $request, $response);
        }
    }

    /**
     * Populate content for the request
     *
     * @param Route $route
     * @param Request $request
     * @param Response $response
     */
    protected function setLastModifiedHeader(Route $route, Request $request, Response $response)
    {
        $content = $route->getContent();

        if ($route->isList()) {
            $dates = array_map(function (array $content) {
                return $content['lastModified'];
            }, $request->attributes->get($content . 's'));

            rsort($dates);

            $lastModified = $dates[0];
        } else {
            $lastModified = $request->attributes->get($content)['lastModified'];
        }

        $response->headers->set('Last-Modified', $lastModified);
    }
}
