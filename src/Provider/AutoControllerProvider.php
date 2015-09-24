<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\Finder\Finder;

/**
 * Provides automatic controller for contents
 */
class AutoControllerProvider implements ControllerProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function connect(Application $app)
    {
        $finder      = new Finder();
        $source      = $app['root'] . $app['src_path'];
        $controllers = $app['controllers_factory'];

        foreach ($finder->directories()->in($source) as $file) {
            $content = $file->getFilename();

            $controllers
                ->get(sprintf('/%s', $content), 'content.controller:index')
                ->contents($content)
                ->bind(sprintf('%s_list', $content));

            $controllers
                ->get(sprintf('/%s/{%s}', $content, $content), 'content.controller:show')
                ->content($content)
                ->bind(sprintf('%s_show', $content));
        }

        return $controllers;
    }
}
