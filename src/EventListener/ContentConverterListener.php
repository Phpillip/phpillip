<?php

namespace Phpillip\EventListener;

use Phpillip\Model\Paginator;
use Phpillip\Routing\Route;
use Phpillip\Service\ContentRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;

/**
 * Convert contents for controllers
 */
class ContentConverterListener implements EventSubscriberInterface
{
    /**
     * Routes
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Content repository
     *
     * @var ContentRepository
     */
    protected $repository;

    /**
     * Constructor
     *
     * @param RouteCollection $routes
     * @param ContentRepository $repository
     */
    public function __construct(RouteCollection $routes, ContentRepository $repository)
    {
        $this->routes     = $routes;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Handler Kernel Controller events
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $route   = $this->routes->get($request->attributes->get('_route'));

        if ($route && $route->hasContent()) {
            $this->populateContent($route, $request);
        }
    }

    /**
     * Populate content for the request
     *
     * @param Route $route
     * @param Request $request
     */
    protected function populateContent(Route $route, Request $request)
    {
        $content = $route->getContent();
        $name    = $content;

        if ($route->isList()) {
            $name .= 's';
            $value = $this->repository->getContents($content, $route->getIndexBy(), $route->getOrder());

            if ($route->isPaginated()) {
                $paginator = new Paginator($value, $route->getPerPage());
                $value     = $paginator->get($request->attributes->get('page'));
                $request->attributes->set('paginator', $paginator);
            }
        } else {
            $value = $this->repository->getContent($content, $request->attributes->get($content));
        }

        $request->attributes->set($name, $value);
    }
}
