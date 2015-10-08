# Content Controller

Phpillip provides a default `ContentController` that supports 3 actions:

- __show:__ Display a single content (suited for [single content](single-content))
- __list:__ Display a full list of content (suited for [content list](../content/helpers.md#content-list))
- __page:__ Display one page of a paginated content list (suited for [pagination](../content/helpers.md#pagination))

## Show

To register a controller that displays a single _achievement_:

``` php
$this
    ->get('/achievements/{achievement}', 'content.controller:show')
    ->content('achievement');
```

The expected template `achievement/show.html.twig` would receive the variable `achievement`.

## List

To register a controller that displays all _achievements_:

``` php
    $this
        ->get('/achievements', 'content.controller:list')
        ->contents('achievement');
```

The expected template `achievement/list.html.twig` would receive the variable `achievements`.

## Paginate

To register a controller that paginates _achievements_:

``` php
    $this
        ->get('/achievements', 'content.controller:page')
        ->paginate('achievement');
```

The expected template `achievement/page.html.twig` would receive the following variables:
- `achievements`: Achievements for the current page
- `page`: Index of the current page
- `pages`: Total number of pages
