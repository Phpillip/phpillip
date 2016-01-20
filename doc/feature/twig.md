# Twig utils

Phpillip register some Twig extensions to provide the following filters and functions:

## Functions

__public:__ Get the relative or absolute url to a file in the public folder.

Get the relative url to a stylesheet:

```twig
<link rel="stylesheet" href="{{ public('css/style.css') }}">
```

Get the absolute url to an image:

```twig
<meta name="twitter:image" content="{{ public('img/twitter.png', true) }}">
```

## Filters

__markdown:__ Parse a mardown string to HTML.

```twig
{{ 'My *markdown* sentence'|markdown }}
```
