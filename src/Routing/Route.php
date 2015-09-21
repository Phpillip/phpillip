<?php

namespace Phpillip\Routing;

use Silex\Route as BaseRoute;

/**
 * Route
 */
class Route extends BaseRoute
{
    /**
     * Content type
     *
     * @var string
     */
    protected $content;

    /**
     * File path
     *
     * @var string
     */
    protected $filePath;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = 'index';

    /**
     * List
     *
     * @var boolean
     */
    protected $list = false;

    /**
     * Hidden from dump
     *
     * @var boolean
     */
    protected $hidden = false;

    /**
     * Mapped on sitemap
     *
     * @var boolean
     */
    protected $mapped = true;

    /**
     * List index
     *
     * @var string
     */
    protected $index;

    /**
     * List sort order
     *
     * @var boolean
     */
    protected $order;

    /**
     * {@inheritdoc}
     */
    public function setPath($pattern)
    {
        parent::setPath($pattern);

        if (preg_match('#^(.*/)?(\w+)\.(\w+)$#i', $pattern, $matches)) {
            $this->setFilePath($matches[1]);
            $this->setFilename($matches[2]);
            $this->format($matches[3]);
        } else {
            $this->setFilePath($pattern);
        }

        return $this;
    }

    /**
     * Set file path
     *
     * @param string $filePath
     *
     * @return Route
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set file name
     *
     * @param string $fileName
     *
     * @return Route
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Content
     *
     * @param string $content
     *
     * @return Route
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Contents
     *
     * @param string $content Type of content to load
     * @param string $index Index the results by the given field name
     * @param string $order Sort content: true for ascending, false for descending
     *
     * @return Route
     */
    public function contents($content, $index = null, $order = true)
    {
        $this->content($content);

        $this->list  = true;
        $this->index = $index;
        $this->order = $order;

        return $this;
    }

    /**
     * Paginate
     *
     * @param string $content
     *
     * @return Route
     */
    public function paginate($content, $index = null, $order = true)
    {
        if (!$this->isPaginated()) {
            $this
                ->contents($content, $index, $order)
                ->setPath($this->getPath() . '/{page}')
                ->value('page', 1)
                ->assert('page', '\d+');
        }

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Has content?
     *
     * @return boolean
     */
    public function hasContent()
    {
        return $this->content !== null;
    }

    /**
     * Is content list?
     *
     * @return boolean
     */
    public function isList()
    {
        return $this->list;
    }

    /**
     * Get index by
     *
     * @return string
     */
    public function getIndexBy()
    {
        return $this->index;
    }

    /**
     * Get sort order
     *
     * @return boolean
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Is pagination enabled?
     *
     * @return boolean
     */
    public function isPaginated()
    {
        return $this->hasDefault('page');
    }

    /**
     * Hide
     *
     * @return Route
     */
    public function hide()
    {
        $this->hidden = true;

        return $this;
    }

    /**
     * Is visible?
     *
     * @return boolean
     */
    public function isVisible()
    {
        return !$this->hidden;
    }

    /**
     * Hide from sitemap
     *
     * @return Route
     */
    public function hideFromSitemap()
    {
        $this->mapped = false;

        return $this;
    }

    /**
     * Is route on sitemap?
     *
     * @return boolean
     */
    public function isMapped()
    {
        return $this->mapped;
    }

    /**
     * Format
     *
     * @param string $format
     *
     * @return Route
     */
    public function format($format)
    {
        $this
            ->value('_format', $format)
            ->assert('_format', $format);

        return $this;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return Route
     */
    public function template($template)
    {
        $this->value('_template', $template);

        return $this;
    }
}
