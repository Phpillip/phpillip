# Property Handlers

Property handlers are responsible for enriching parsed contents by providing automatic properties or casting properties as a certain type.

Phpillip provides a default set of Property Handlers (see [Retrieving content](../content/retrieving-content.md)).

And you're able to add your own to fit your needs!

## Create a custom property handler

_Create a class_ that implements the `Phpillip\Behavior\PropertyHandlerInterface`:

``` php
<?php

namespace PropertyHandler;

use Phpillip\Behavior\PropertyHandlerInterface;

/**
 * Parse a certain property a certain way
 */
class MyPropertyHandler implements PropertyHandlerInterface
{
    /**
     * Get supported property name
     *
     * @return string
     */
    public function getProperty()
    {
        return 'my_property';
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
     * Handle property
     *
     * @param mixed $value
     * @param array $context
     *
     * @return mixed
     */
    public function handle($value, array $context)
    {
        return $this->doSomethingWith($value);
    }
}
```

_Register your property handler_ in the Content Repository:

``` php
$app['content_repository']->addPropertyHandler(new MyPropertyHandler());
```

In this example, the __handle__ method will be called on every _my_property_ properties when the content data _isSupported_.
