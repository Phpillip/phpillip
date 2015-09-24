# Supported formats

Phpillip already support the following content formats:

Format   | Extension
-------- | ---------
Markdown | *.md
YAML     | *.yml
JSON     | *.json
XML      | *.xml

## The Markdown header

The markdown format is a bit special, the content of the file will be parsed and converted to HTML and stored into a `content` key.

You can define key and value for content by writing a YAML header:

``` markdown
---
title: "My first blog post"
description: "A fine blog post, you will like it."
---

# My post title

My content goes _here_!
```

This file would be decoded as the followin array:

``` php
[
    'title'       => "My first blog post",
    'description' => "My first blog post",
    'content'     => '<h1>My post title</h1><p>My content goes <em>here</em>!</p>'
]
```

## Support your own format

In Phpillip, the `decoder` service is responsible for parsing content.
The decoder is a Symfony _Serializer_ filled a Symfony _Decoder_ for each format.

_If your curious, have a look at `Phpillip\Provider\DecoderServiceProvider`._

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

