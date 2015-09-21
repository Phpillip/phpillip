<?php

namespace Phpillip\PropertyHandler;

use Phpillip\Behavior\PropertyHandlerInterface;
use Phpillip\Service\ContentRepository;

/**
 * Set "slug" property from file name if not specified
 */
class SlugPropertyHandler implements PropertyHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return 'slug';
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported(array $data)
    {
        return !isset($data[$this->getProperty()]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($value, array $context)
    {
        return ContentRepository::getName($context['file']);
    }
}
