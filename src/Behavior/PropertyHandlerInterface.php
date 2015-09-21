<?php

namespace Phpillip\Behavior;

/**
 * Property Handler interface
 */
interface PropertyHandlerInterface
{
    /**
     * Get supported property name
     *
     * @return string
     */
    public function getProperty();

    /**
     * Is data supported?
     *
     * @param array $data
     *
     * @return boolean
     */
    public function isSupported(array $data);

    /**
     * Handler property
     *
     * @param mixed $value
     * @param array $context
     *
     * @return mixed
     */
    public function handle($value, array $context);
}
