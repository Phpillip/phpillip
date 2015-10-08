# Supported formats

Phpillip already supports the following content formats:

Format   | Extension
-------- | ---------
Markdown | *.md
YAML     | *.yml
JSON     | *.json
XML      | *.xml

## Markdown

The Markdown format gets a special treatment and [has its own documentation section](../content/markdown.md).

## Support your own format

In Phpillip, the `decoder` service is responsible for parsing content.
The decoder is a Symfony _Serializer_ filled with a Symfony _Decoder_ for each format.

> If you're curious, have a look at `Phpillip\Provider\DecoderServiceProvider`.

To support a new format, just create class that implement `Symfony\Component\Serializer\Encoder\DecoderInterface`:

``` php
<?php

namespace Decoder;

use Symfony\Component\Serializer\Encoder\DecoderInterface;

/**
 * Decode my custom format
 */
class MyCustomDecoder implements DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsDecoding($format)
    {
        return $format === 'my-custom-format';
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $format, array $context = array())
    {
        return $this->doSomethingWith($data);
    }
}
```

And add it to Phpillip content encoders:

``` php
$app['content_encoders'][] = new MyCustomDecoder();
```

Files matching your custom format will now be parsed among the others.

__Note:__ To know more about how Phpillip parses contents have a look at [property handlers](../content/property-handlers.md).
