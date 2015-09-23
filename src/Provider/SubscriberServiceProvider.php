<?php

namespace Phpillip\Provider;

use Phpillip\EventListener\ContentConverterListener;
use Phpillip\EventListener\LastModifiedListener;
use Phpillip\EventListener\TemplateListener;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Subscriber Service Provider
 */
class SubscriberServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['dispatcher']->addSubscriber(new ContentConverterListener($app['routes'], $app['content_repository']));
        $app['dispatcher']->addSubscriber(new LastModifiedListener($app['routes']));
        $app['dispatcher']->addSubscriber(new TemplateListener($app['routes'], $app['twig']));
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
