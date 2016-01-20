<?php

namespace Phpillip;

use Phpillip\Config\Configurator;
use Phpillip\Provider as PhpillipProvider;
use Silex\Application as BaseApplication;
use Silex\Provider as SilexProvider;

/**
 * Phpillip Application
 */
class Application extends BaseApplication
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $values = array())
    {
        parent::__construct(array_merge_recursive(
            ['root' => $this->getRoot()],
            $this->getConfiguration(),
            $values
        ));

        $this->registerServiceProviders();
    }

    /**
     * Register service providers
     */
    public function registerServiceProviders()
    {
        $this->register(new SilexProvider\HttpFragmentServiceProvider());
        $this->register(new SilexProvider\UrlGeneratorServiceProvider());
        $this->register(new SilexProvider\ServiceControllerServiceProvider());
        $this->register(new PhpillipProvider\InformatorServiceProvider());
        $this->register(new PhpillipProvider\PygmentsServiceProvider());
        $this->register(new PhpillipProvider\ParsedownServiceProvider());
        $this->register(new PhpillipProvider\DecoderServiceProvider());
        $this->register(new PhpillipProvider\ContentServiceProvider());
        $this->register(new PhpillipProvider\TwigServiceProvider());
        $this->register(new PhpillipProvider\SubscriberServiceProvider());
        $this->register(new PhpillipProvider\ContentControllerServiceProvider());
    }

    /**
     * Load and return configuration
     *
     * @return array
     */
    protected function getConfiguration()
    {
        $configurator = new Configurator($this, [$this->getRoot() . '/Resources/config']);

        return $configurator->getConfiguration();
    }

    /**
     * Get root directory (the by Symfony Kernel's way)
     *
     * @return string
     */
    protected function getRoot()
    {
        $reflection = new \ReflectionObject($this);

        return str_replace('\\', '/', dirname($reflection->getFileName()));
    }
}
