<?php

namespace Phpillip\Provider;

use Exception;
use Phpillip\Config\Configurator;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Insert configuration into Application using the Configurator
 */
class ConfigurationServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $configurator = new Configurator($app, [$app['root'] . '/Resources/config']);

        foreach ($configurator->getConfiguration() as $key => $value) {
            $app[$key] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
