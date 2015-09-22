# Content Controller

Phpillip provides a default `ContentController` that support 3 actions:

- __list:__ Display a full list of content (suited for [contents]())
- __page:__ Display a one page of a paginated content list (suited for [paginate]())
- __show:__ Display a single content (suited for [paginate]())

## List

To register a controller that display all _achievements_:

``` php
    $this->get('/achievements', 'content.controller:list')->contents('achievement');
```

The expected template `achievement/list.html.twig` would receives the variable `achievements`.

## Show

To register a controller that display a single _achievement_:

``` php
$this->get('/blog/{post}', 'content.controller:show')->content('post');
```

The expected template `post/show.html.twig` would receives the variable `post`.

## Paginate

To register a controller that paginate _achievements_:

``` php
    $this->get('/blog/{page}', 'content.controller:page')->paginate('post');
```

The expected template `achievement/page.html.twig` would receives the following variables:
- `achievements`: Achievements for the current page
- `page`: Index of the current page
- `pages`: Total number of pages
