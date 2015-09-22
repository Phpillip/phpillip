<?php

namespace Phpillip\Controller;

use Phpillip\Application;
use Phpillip\Model\Paginator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Content controller
 */
class ContentController
{
    /**
     * List of contents
     *
     * @param Request $request
     * @param Application $app
     *
     * @return array
     */
    public function index(Request $request, Application $app)
    {
        return $this->extractViewParameters($request, ['app']);
    }

    /**
     * Paginated list of contents
     *
     * @param Request $request
     * @param Application $app
     * @param Paginator $paginator
     *
     * @return array
     */
    public function page(Request $request, Application $app, Paginator $paginator)
    {
        return array_merge(
            ['pages' => count($paginator)],
            $this->extractViewParameters($request, ['app', 'paginator'])
        );
    }

    /**
     * Show a single content
     *
     * @param Request $request
     * @param Application $app
     *
     * @return array
     */
    public function show(Request $request, Application $app)
    {
        return $this->extractViewParameters($request, ['app']);
    }

    /**
     * Extract view parameters from Request attributes
     *
     * @param Request $request
     * @param array $exclude Keys to exclude from view
     *
     * @return array
     */
    protected function extractViewParameters(Request $request, array $exclude = [])
    {
        $parameters = [];

        foreach ($request->attributes as $key => $value) {
            if (strpos($key, '_') !== 0 && !in_array($key, $exclude)) {
                $parameters[$key] = $value;
            }
        }

        return $parameters;
    }
}
