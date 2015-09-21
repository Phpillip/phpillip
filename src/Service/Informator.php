<?php

namespace Phpillip\Service;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Informator
 */
class Informator
{
    /**
     * Url Generator
     *
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * Injecting dependencies
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Before request
     *
     * @param Request $request
     * @param Application $app
     */
    public function beforeRequest(Request $request, Application $app)
    {
        $url = $this->urlGenerator->generate(
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $request->attributes->set('_canonical', $url);

        $app['twig']->addGlobal('canonical', $url);
    }
}
