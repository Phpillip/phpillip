<?php

namespace Phpillip\Twig;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Twig_Extension as Extension;
use Twig_SimpleFunction as SimpleFunction;

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
     * Set root directory
     *
     * @param $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new SimpleFunction('public', [$this, 'getPublicUrl']),
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
