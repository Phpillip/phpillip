<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Phpillip\PropertyHandler;
use Phpillip\Service\ContentRepository;

/**
 * Content Service Provider
 */
class ContentServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['content_repository'] = $app->share(function ($app) {
            return new ContentRepository($app['decoder'], $app['root'] . $app['src_path']);
        });

        $app['content_repository']
            ->addPropertyHandler(new PropertyHandler\DateTimePropertyHandler())
            ->addPropertyHandler(new PropertyHandler\IntegerPropertyHandler('weight'))
            ->addPropertyHandler(new PropertyHandler\LastModifiedPropertyHandler())
            ->addPropertyHandler(new PropertyHandler\SlugPropertyHandler());
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
