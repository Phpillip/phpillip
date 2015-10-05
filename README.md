# ![](http://phpillip.github.io/phpillip.svg)

> Phpillip is [Hugo](https://gohugo.io/)'s cousin.

## What

Phpillip is static website generator written in PHP and powered by [Silex](http://silex.sensiolabs.org/) and [Symfony components](http://symfony.com/doc/current/components/index.html).

It basically dumps your Silex application to static HTML files in a `/dist` folder.

The result directory is meant to be served by an HTTP Server like [apache](http://apache.org) and [nginx](http://www.nginx.com) or published to static website services like [Github Pages](https://pages.github.com/).

*It's particularly fit for __blogging__, __documentation__ and __showcase__.*

## How

Phpillip is a [Silex](http://silex.sensiolabs.org/) application.

The __build process__:
- Loop through all declared _routes_ in the Application
- Load content associated with the route (if any) from file
- Call each route with its content in a _Request_
- Dump the _Response_ content in a file

It supports as many format as you need.

It uses the powerful [Twig](http://twig.sensiolabs.org/) engine for templating.

## Why

Phpillip is meant to be:

- Highly __extensible__
- Friendly with __Symfony__ developers
- Clear, simple and clean

## Getting started

Get your static website:

1. Bootstrap a Phpillip project
2. Write your content
3. Declare your routes and controllers
4. Provide templates
5. Build the static website

### 1. Bootstrap

To bootstrap a new [Phpillip](https://github.com/Phpillip/phpillip) project:

``` bash
composer create-project phpillip/phpillip-standard my_app
cd my_app
```

### 2. Write content

Write your content file `[my-content-slug].[format]` in `src/Resources/data/[my-content-type]/`:

__Example__ `src/Resources/data/article/why-use-phpillip.md`:

```
---
title: Why use Phpillip?
---

# Why use Phpillip

Why not!
```

### 3. Declare routes and controllers

Phpillip is a Silex application, so you can declare a route and its controller [the same way you would in Silex](http://silex.sensiolabs.org/doc/usage.html#routing):

A closure:

``` php
$this->get('/', function () { return []; })->template('index.html.twig');
```

Your own controller class in 'src/Controller':

``` php
$this->get('/blog', 'Controller\\BlogController::index');
```

A controller service (here the Phpillip content controller service):

``` php
$this->get('/blog/{post}', 'content.controller:show')->content('post');
```

Phpillip gives you [many helpers](doc/feature/helpers.md) to automate content loading for your routes.

### 4. Provide templates

Write the Twig templates corresponding to your routes and controllers in `src/Resources/views/`

If you use the default Phpillip routes and controller, you'll need to provide:

The list template:

_File:_ `[my-content-type]/index.html.twig`
_Variables:_ An array of contents, named `[content-type]s`.

``` twig
{% extends 'base.html.twig' %}
{% block content %}
    {% for article in articles %}
        <a href="{{ path('article', {article: article.slug}) }}">
            {{ article.title }}
        </a>
    {% endfor %}
{% endblock %}
```

The single content page template:

_File:_ `[my-content-type]/show.html.twig`
_Variables:_ The content as an associative array, named `[content-type]`.

``` twig
{% extends 'base.html.twig' %}
{% block content %}
    {{ article.content }}
{% endblock %}
```

### 5. Build

Build the static files to `/dist` with the Phpillip build command:

    bin/console phpillip:build

You're done!

## Going further:

About Phpillip's __features__:
- [Helpers: Param Converters and other route shortcuts](doc/feature/helpers.md)
- [Sitemap](doc/feature/sitemap.md)

About __content__:

- [Supported formats](doc/content/formats.md)
- [Markdown](doc/content/markdown.md)
- [Retrieving content](doc/content/retrieving-content.md)
- [Property handlers](doc/content/property-handlers.md)

About __controllers__:

- [Phpillip's default content controller](doc/controller/content.md)
- [Custom controller classes](doc/controller/custom.md)
- [Specifying output format](doc/controller/format.md)

About the __console__:

- [Phpillip's Console](doc/console/commands.md)

## Contribution

Any kind of [contribution](doc/more/contribution.md) is very welcome!

## Directory structure

```
# Sources directory
src/
    # Your Silex Application in which your declare routes, services, ...
    Application.php

    # Your controller classes (optional)
    # This is only a recommandation, you can put controllers wherever you like
    /Controller
        MyController.php

    # Resources
    /Resources

        # Configuration files directory
        config/
            # Phpillip configuration
            config.yml

        # Content directory
        data/
            # Create a directory for each content type
            post/
                # Your 'post' contents goes here
                my-first-post.md
                a-post-in-json.json

        # Public directory
        public/
            # All public directory content will be exposed in 'dist'
            css/
                style.css

        # Views directory
        views/
            # Your twig templates
            base.html.twig
            blog/
                index.html.twig
                show.html.twig

# Destination directory
dist/
    # The static files will be dumped in here

```
