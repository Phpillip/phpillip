<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Phpillip\Service\Informator;

/**
 * Informator Service Provider
 */
class InformatorServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['informator'] = $app->share(function ($app) {
            return new Informator($app['url_generator']);
        });

        $app->before([$app['informator'], 'beforeRequest']);
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
