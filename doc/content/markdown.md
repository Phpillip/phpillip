# Markdown

Phpillip is designed to parse Markdown as a first-choice content format.

Markdown provides a cleanway of writing structured text and is easily converted to HTML.

Phpillip rely on [Parsedown](http://parsedown.org/) for parsing Markdown, in the Markdown decoder.

__Note:__ if you're not happy with it, you can always [setup you own encoder](../content/formats.md).

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

## Syntax highlighting

Thanks to Parsedown, Phpillip supports Github Flavored Markdown.
That means you can define a language for your code blocks:

```markdown
    ```php
    $this->isPhp();
    ```

    ```javascript
    this.isJavascript();
    ```
```

Phpillip provides syntax highlighting for code block that define a language. He entrust [Pygments](http://pygments.org/), a python command line tool, to do the job.

In order to get that feature, you'll need to install Pygments:

    pip install Pygments

_Note:_ requires [Python](https://www.python.org/downloads/)
