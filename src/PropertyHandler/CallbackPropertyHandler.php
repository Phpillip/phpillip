<?php

namespace Phpillip\PropertyHandler;

use Phpillip\Behavior\PropertyHandlerInterface;

/**
 * Applies the given callback on the given property
 */
class CallbackPropertyHandler implements PropertyHandlerInterface
{
    /**
     * Property name
     *
     * @var string
     */
    protected $property;

    /**
     * Callback
     *
     * @var callable
     */
    protected $callable;

    /**
     * Constructor
     *
     * @param string $property
     * @param callable $callback
     */
    public function __construct($property, $callback)
    {
        $this->property = $property;
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Is data supported?
     *
     * @param array $data
     *
     * @return boolean
     */
    public function isSupported(array $data)
    {
        return isset($data[$this->getProperty()]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($value, array $context)
    {
        return call_user_func($this->callback, $value, $context);
    }
}
