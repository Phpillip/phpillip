<?php

namespace Phpillip;

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
        parent::__construct(array_merge($values, ['root' => $this->getRoot()]));

        $this->register(new SilexProvider\HttpFragmentServiceProvider());
        $this->register(new SilexProvider\UrlGeneratorServiceProvider());
        $this->register(new PhpillipProvider\ConfigurationServiceProvider());
        $this->register(new PhpillipProvider\DecoderServiceProvider());
        $this->register(new PhpillipProvider\ContentServiceProvider());
        $this->register(new PhpillipProvider\TwigServiceProvider());
        $this->register(new PhpillipProvider\InformatorServiceProvider());
        $this->register(new PhpillipProvider\SubscriberServiceProvider());
    }

    /**
     * Get root directory (the by Symfony Kernel's way)
     *
     * @return string
     */
    private function getRoot()
    {
        $reflection = new \ReflectionObject($this);

        return str_replace('\\', '/', dirname($reflection->getFileName()));
    }
}
