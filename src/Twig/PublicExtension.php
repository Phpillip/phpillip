<?php

namespace Phpillip\Twig;

use Twig_Extension as Extension;
use Twig_SimpleFilter as SimpleFilter;

/**
 * Public extension
 */
class PublicExtension extends Extension
{
    /**
     * Root url
     *
     * @var string
     */
    protected $root;

    /**
     * Before request
     *
     * @param Request $request
     * @param Application $app
     */
    public function beforeRequest(Request $request, Application $app)
    {
        $this->root = $request->attributes->get('_root');;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new SimpleFilter('public', [$this, 'getPublicUrl']),
        ];
    }

    /**
     * Get public url for the given path
     *
     * @param string $path The path to expose
     * @param boolean $absolute Whether or not the url should be absolute
     *
     * @return string
     */
    public function getPublicUrl($path, $absolute = false)
    {
        return sprintf('%s/%s', $absolute ? $this->root : null, ltrim($path, '/'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'public_extension';
    }
}
