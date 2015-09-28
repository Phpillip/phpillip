# Supported formats

Phpillip already support the following content formats:

Format   | Extension
-------- | ---------
Markdown | *.md
YAML     | *.yml
JSON     | *.json
XML      | *.xml

## The Markdown header

YAML, JSON and XML are key/values formats, so they can be easily parsed as associative array.

The Markdown can't. That's why Phpillip's markdown parser is a bit special.

The content of the file is parsed and converted to HTML.
The result is then stored into the `content` key of an associative array.

You can define additional keys and values for content by writing a YAML header:

``` markdown
---
title: "My first blog post"
description: "A fine blog post, you will like it."
---

# My post title

My content goes _here_!
```

This file would be decoded as the following array:

``` php
[
    'title'       => 'My first blog post',
    'description' => 'A fine blog post, you will like it.',
    'content'     => '<h1>My post title</h1><p>My content goes <em>here</em>!</p>'
]
```

## Support your own format

In Phpillip, the `decoder` service is responsible for parsing content.
The decoder is a Symfony _Serializer_ filled a Symfony _Decoder_ for each format.

> If your curious, have a look at `Phpillip\Provider\DecoderServiceProvider`.

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

Files matchin your custom format will now be parsed among the others.

__Note:__ To know more about how Phpillip parses contents have a look at [property handlers](../content/property-handlers.md).
