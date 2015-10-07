# Custom Controller

You can declare your own controllers as classes:

``` php
<?php

namespace Controller;

use Phpillip\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Product controller
 */
class ProductController
{
    /**
     * List products
     *
     * @param Request $request
     * @param Application $app
     * @param array $posts
     *
     * @return array
     */
    public function index(Request $request, Application $app)
    {
        return [
            'products' => $app['content_repository']->getContents('product'),
        ];
    }

    /**
     * Show a product
     *
     * @param Request $request
     * @param Application $app
     * @param string $reference
     *
     * @return array
     */
    public function show(Request $request, Application $app, array $reference)
    {
        return [
            'products' => $app['content_repository']->getContent('product', $reference),
        ];
    }
}
```

Register your controller in the app:

``` php
    $this->get('/products', 'Controller\\ProductController:index');
    $this->get('/product/{reference}', 'Controller\\ProductController:show');
```

The expected template `achievement/list.html.twig` would receive the variable `achievements`.
