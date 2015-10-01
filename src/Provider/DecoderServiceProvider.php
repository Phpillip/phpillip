<?php

namespace Phpillip\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\Serializer\Encoder;
use Symfony\Component\Serializer\Serializer;
use Phpillip\Encoder as PhpillipEncoder;

/**
 * Decoder Service Provider
 */
class DecoderServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app['decoder.xml'] = $app->share(function ($app) {
            return new Encoder\XmlEncoder();
        });

        $app['decoder.json'] = $app->share(function ($app) {
            return new Encoder\JsonEncoder();
        });

        $app['decoder.markdown'] = $app->share(function ($app) {
            return new PhpillipEncoder\MarkdownDecoder($app['parsedown']);
        });

        $app['decoder.yaml'] = $app->share(function ($app) {
            return new PhpillipEncoder\YamlEncoder();
        });

        $app['content_decoders'] = [
            $app['decoder.xml'],
            $app['decoder.json'],
            $app['decoder.yaml'],
            $app['decoder.markdown'],
        ];

        $app['decoder'] = $app->share(function ($app) {
            return new Serializer([], $app['content_decoders']);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
