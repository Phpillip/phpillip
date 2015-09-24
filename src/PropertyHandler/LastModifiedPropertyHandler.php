<?php

namespace Phpillip\PropertyHandler;

use DateTime;
use Phpillip\Behavior\PropertyHandlerInterface;

/**
 * Set a "LastModified" property based on file date
 */
class LastModifiedPropertyHandler implements PropertyHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return 'lastModified';
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
        $lastModified = new DateTime();
        $lastModified->setTimestamp($context['file']->getMTime());

        return $lastModified;
    }
}
