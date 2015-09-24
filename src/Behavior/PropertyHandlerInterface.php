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
     * @param array $data The content being parsed
     *
     * @return boolean
     */
    public function isSupported(array $data);

    /**
     * Handle property
     *
     * @param mixed $value The property value
     * @param array $context The context of parsing process
     *
     * @return mixed
     */
    public function handle($value, array $context);
}
