<?php

namespace Phpillip\Config\Loader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * YAML Config Loader
 */
class YamlConfigLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $config = Yaml::parse(file_get_contents($resource));

        if (isset($config['imports'])) {
            foreach ($config['imports'] as $resource) {
                $config = array_merge($config, $this->import($resource));
            }
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}
