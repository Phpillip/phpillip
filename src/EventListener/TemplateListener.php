<?php

namespace Phpillip\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig_Environment as Twig;

/**
 * Find and render the proper template for unfinished controller response
 */
class TemplateListener implements EventSubscriberInterface
{
    /**
     * Twig rendering engine
     *
     * @var Twig_Environment
     */
    protected $templating;

    /**
     * Templating
     *
     * @param Twig_Environment $templating
     */
    public function __construct(Twig $templating)
    {
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
     * Handles KErnel Controller events
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
        $parameters = $event->getControllerResult();

        if (is_array($parameters) && $template = $request->attributes->get('_template')) {
            $event->setControllerResult($this->templating->render($template, $parameters));
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

        if ('rss' == $format = $request->attributes->get('_format', 'html')) {
            return '@phpillip/rss.xml.twig';
        }

        if (!is_array($controller) || !is_object($controller[0])) {
            return null;
        }

        if (!preg_match('#Controller\\\(.+)Controller$#', get_class($controller[0]), $matches)) {
            return null;
        }

        return sprintf('%s/%s.%s.twig', $matches[1], $controller[1], $format);
    }
}
