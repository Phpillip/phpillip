<?php

namespace Phpillip\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;
use Symfony\Component\Templating\EngineInterface;

/**
 * Find and render the proper template for unfinished controller response
 */
class TemplateListener implements EventSubscriberInterface
{
    /**
     * Twig rendering engine
     *
     * @var EngineInterface
     */
    protected $templating;

    /**
     * Routes
     *
     * @var RouteCollection
     */
    protected $routes;

    /**
     * Constructor
     *
     * @param Router $router
     * @param EngineInterface $templating
     */
    public function __construct(Router $router, EngineInterface $templating)
    {
        $this->routes     = $router->getRouteCollection();
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::VIEW       => 'onKernelView',
        ];
    }

    /**
     * Handles Kernel Controller events
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if ($template = $this->getTemplate($request, $event->getController())) {
            $request->attributes->set('_template', $template);
        }
    }

    /**
     * Handles Kernel View events
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request    = $event->getRequest();
        $response   = $event->getResponse();
        $parameters = $event->getControllerResult();

        if (!$response instanceof Response && $template = $request->attributes->get('_template')) {
            $event->setResponse($this->templating->renderResponse($template, $parameters));
        }
    }

    /**
     * Get template from the given request and controller
     *
     * @param Request $request
     * @param mixed $controller
     *
     * @return string|null
     */
    protected function getTemplate(Request $request, $controller)
    {
        if ($request->attributes->has('_template')) {
            return null;
        }

        $format    = $request->attributes->get('_format', 'html');
        $templates = [];

        if ($controllerInfo = $this->parseController($controller)) {
            $template = sprintf('%s/%s.%s.twig', $controllerInfo['name'], $controllerInfo['action'], $format);

            if ($this->templating->exists($template)) {
                return $template;
            } else {
                $templates[] = $template;
            }
        }

        $route = $this->routes->get($request->attributes->get('_route'));

        if ($route && $route->hasContent()) {
            $template = sprintf('%s/%s.%s.twig', $route->getContent(), $route->isList() ? 'list' : 'show', $format);

            if ($this->templating->exists($template)) {
                return $template;
            } else {
                $templates[] = $template;
            }
        }

        return array_pop($templates);
    }

    /**
     * Parse controller to extract its name
     *
     * @param mixed $controller
     *
     * @return string
     */
    protected function parseController($controller)
    {
        if (!is_array($controller) || !is_object($controller[0]) || !isset($controller[1])) {
            return null;
        }

        if (!preg_match('#Controller\\\(.+)Controller$#', get_class($controller[0]), $matches)) {
            return null;
        }

        return ['name' => $matches[1], 'action' => $controller[1]];
    }
}
