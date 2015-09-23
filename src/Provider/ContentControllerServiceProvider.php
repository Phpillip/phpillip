<?php

namespace Phpillip\Provider;

use Phpillip\Controller\ContentController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Provide a default Controller for content
 */
class ContentControllerServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['content.controller'] = $app->share(function () {
            return new ContentController();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
