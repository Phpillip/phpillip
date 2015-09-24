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
        $app['content_decoders'] = [
            new Encoder\XmlEncoder(),
            new Encoder\JsonEncoder(),
            new PhpillipEncoder\YamlEncoder(),
            new PhpillipEncoder\MarkdownDecoder()
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
