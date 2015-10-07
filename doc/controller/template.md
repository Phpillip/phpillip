# Template resolution

Phpillip provides the same type of template resolution that you get in Symfony.
When a Controller doesn't return a _Response_, Phpillip will try to create one by finding and rendering a matching template.

## For content routes

If a route is declared as having a _content_, Phpillip will look for the  template: `[content_type]/[show|list].[format].twig`

```php
// For single content:
$app->get('/blog/{post}', 'content.controller:show')->content('post');

// The template: 'src/Resources/views/post/show.html.twig'
```


```php
// For several contents:
$app->get('/blog', 'content.controller:show')->paginate('post');
// or
$app->get('/blog', 'content.controller:show')->contents('post');

// The template: 'src/Resources/views/post/list.html.twig'
```

## For Class controllers

If you declare you controller as a Class Controller:

```php
$app->get('/blog', 'Acme\Controller\BlogController::index');
```

Phpillip will look for the template `[ControllerName]/[actionName].[format].twig` (just like Symfony does).

In our example: `src/Resources/views/Blog/index.html.twig`

__Note:__ Phpillip looks for a twig template matching the format of your route.

