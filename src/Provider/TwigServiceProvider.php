<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\Provider\TwigServiceProvider as BaseTwigServiceProvider;
use Phpillip\Twig\MarkdownExtension;
use Phpillip\Twig\PublicExtension;

/**
 * Twig integration for Silex.
 */
class TwigServiceProvider extends BaseTwigServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        parent::register($app);

        // Set twig path
        $app['twig.path'] = $app['root'] . $app['twig_path'];

        // Set Phpillip view path
        $app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/views', 'phpillip');

        // Add parameters to Twig globals
        $app['twig']->addGlobal('parameters', $app['parameters']);

        // Set up Public Extension as a service
        $app['twig_extension.public'] = $app->share(function ($app) {
            return new PublicExtension();
        });
        $app->before([$app['twig_extension.public'], 'beforeRequest']);

        // Register extensions
        $app['twig']->addExtension(new MarkdownExtension($app['parsedown']));
        $app['twig']->addExtension($app['twig.extension.public']);
    }
}
