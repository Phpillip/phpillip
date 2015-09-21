<?php

namespace Phpillip\Encoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Encodes Yaml data
 */
class YamlEncoder implements EncoderInterface, DecoderInterface
{
    /**
     * Supported format
     */
    const FORMAT = 'yaml';

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = array())
    {
        return Yaml::dump($data);
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        return Yaml::parse($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return self::FORMAT === $format;
    }
}
