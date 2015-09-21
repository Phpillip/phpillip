<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\Provider\TwigServiceProvider as BaseTwigServiceProvider;
use Phpillip\EventListener\TemplateListener;
use Phpillip\Twig\MarkdownExtension;

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

        $app['twig.path'] = $app['root'] . $app['twig_path'];
        $app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/views', 'phpillip');
        $app['twig']->addGlobal('parameters', $app['parameters']);

        $app['dispatcher']->addSubscriber(new TemplateListener($app['twig']));
    }

    protected function registerExtentions(Twig $twig)
    {
        $twig->addExtension(new MarkdownExtension());
    }
}
