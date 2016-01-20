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
        if ($canonical = $this->getCanonicalUrl($request)) {
            $request->attributes->set('_canonical', $canonical);
            $app['twig']->addGlobal('canonical', $canonical);
        }

        if ($root = $this->getRootUrl($request)) {
            $request->attributes->set('_root', $root);
            $app['twig']->addGlobal('root', $root);
        }
    }

    /**
     * Get canonical URL
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getCanonicalUrl(Request $request)
    {
        return $this->urlGenerator->generate(
            $request->attributes->get('_route'),
            $request->attributes->get('_route_params'),
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * Get root URL
     *
     * @param Request $request
     *
     * @return string
     */
    protected function getRootUrl(Request $request)
    {
        return sprintf('%s://%s', $request->getScheme(), $request->getHost());
    }
}
