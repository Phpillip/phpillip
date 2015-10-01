<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Parsedown Service Provider
 */
class PygmentsServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        if ($app['pygments_class']::isAvailable()) {
            $app['pygments'] = $app->share(function ($app) {
                return new $app['pygments_class']();
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
