# Phpillip

> Phillip is [Hugo](https://gohugo.io/)'s cousin.

# What:

Phpillip allow you to generate _static_ websites from file-stored contents.
The result directory is meant to be served by an HTTP Server like [apache](http://apache.org) and [nginx](http://www.nginx.com) or static page servers like [Github Pages](https://pages.github.com/).

_It's particularly fit for blogging, documentation and showcase._

# How:

It's written in PHP, using [Silex](http://silex.sensiolabs.org/) and it's meant to be:

- __Clear, simple and strong__
- __Highly extendable and customisable__
- __Symfony developers Friendly__

## Usage

### Content

#### Writing content

Write your contents: `[my-content-slug].[format]` in `data/[content-type]/`

Example: `data/article/why-use-phpillip.md`

#### Reading content

The following content format are natively supported:

- Markdown
- YML
- XML
- JSON

You're free to support any format you want by adding new __decoders__.

#### Retrieving content:

```php
    # Retrieve a content by name:
    $app['content_repository']->getContent('article', 'why-use-phpillip');

    # Retrieve all contents of a type:
    $app['content_repository']->getContents('article');

    # Retrieve all contents of a type, ordered by date, most recent first:
    $app['content_repository']->getContents('article', 'date', false);
```

## Design

It's built with [Silex](http://silex.sensiolabs.org/) and uses Symfony components you're used to work with: [Twig](http://twig.sensiolabs.org/) as a rendering engine, Routes, Controllers, Commands, ...

The build process is simple: __dump all declared routes__.

## Directory structure

```
src
    |- Controller
        |- # Your controllers
    |- Resources
        |- config
            |- config.yml # Route config
        |- views
            |- # Your views
        |- public
            |- # Anything you want to expose

```
