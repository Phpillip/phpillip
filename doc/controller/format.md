# Route format

Specifiyng the format of a route will determine the extention of the output file during the build.

By default, all routes are treated as _HTML_ and therefor dumped as `.html` files.

Phillip rely on the _Response_ `Content-Type` header to determine the format of a route.

To control the output format of a route, you just need to configure the _Response_ with the desired content type.

There are 3 ways to do it:

## Do it, litteraly:

```php
function () {
    // Will output a '.txt' file
    return new Response('Hello', 200, ['Content-Type' => 'text/plain'])
}

function () {
    // Will output a '.json' file
    return new JsonResponse($data);
}
```

## Set the *_format* Request attribute

In Symfony, Response content type is by default determined by the format of the Request.

So you can define the output format of a route by setting the _Request_ attribute `_format`. And Phpillip provides you with a `format` method on the route to do just that:

```php
// Will output a '.txt' file
$app->get('/hello')->format('txt');
```

__Note__: Remember that the _Response_ expects a Mime-Type (e.g _text/html_) but the _Request_ expects a format (e.g. _html_).

Finally you can set the format of the route by explicitely naming the file in the url pattern:

```php
// Will output a '.json' file
$app->get('/hello.json');
```

# File name

Additionaly, you can choose a custom name for the output file.

With the `setFileName` method:

```php
// Will output a '404.html' file
$app->get('/404')->setFilename('404');
```

Or directly in url pattern:

```php
// Will output a 'feed.rss' file
$app->get('/feed.rss');
```

__Note:__ The default output file name is `index`.
