<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Parsedown Service Provider
 */
class ParsedownServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['parsedown'] = $app->share(function ($app) {
            return new $app['parsedown_class'](isset($app['pygments']) ? $app['pygments'] : null);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
