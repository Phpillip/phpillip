<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Host Service Provider
 */
class HostServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        if (isset($app['host'])) {
            $app['url_generator']->getContext()->setHost($app['host']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
