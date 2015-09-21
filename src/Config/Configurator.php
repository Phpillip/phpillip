<?php

namespace Phpillip\Config;

use Phpillip\Config\Definition\PhpillipConfiguration;
use Phpillip\Config\Loader\YamlConfigLoader;
use Silex\Application;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

/**
 * Parse and merge configuration from several directories
 */
class Configurator
{
    /**
     * Application
     *
     * @var Application
     */
    protected $app;

    /**
     * Configuration definition
     *
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * File locator
     *
     * @var FileLocator
     */
    protected $locator;

    /**
     * Resolver
     *
     * @var LoaderResolver
     */
    protected $resolver;

    /**
     * File loader
     *
     * @var DelegatingLoader
     */
    protected $loader;

    /**
     * Constructor
     *
     * @param Application $app
     * @param array $configDirectories
     */
    public function __construct(Application $app, array $configDirectories = [])
    {
        $this->app           = $app;
        $this->locator       = new FileLocator($configDirectories);
        $this->resolver      = new LoaderResolver([new YamlConfigLoader($this->locator)]);
        $this->loader        = new DelegatingLoader($this->resolver);
        $this->processor     = new Processor();
        $this->configuration = new PhpillipConfiguration();
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        $configurationFiles = $this->locator->locate('config.yml', null, false);
        $configurations     = [];

        foreach ($configurationFiles as $path) {
            $configurations[] = $this->loader->load($path);
        }

        return $this->processor->processConfiguration($this->configuration, $configurations);
    }
}
