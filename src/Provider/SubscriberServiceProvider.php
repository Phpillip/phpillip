<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Phpillip\EventListener;

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
        $app['dispatcher']->addSubscriber(new EventListener\ContentConverterListener($app['routes'], $app['content_repository']));
        $app['dispatcher']->addSubscriber(new EventListener\LastModifiedListener($app['routes']));
        $app['dispatcher']->addSubscriber(new EventListener\TemplateListener($app['routes'], $app['twig']));
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
