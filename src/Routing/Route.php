<?php

namespace Phpillip\Routing;

use Symfony\Component\Routing\Route as BaseRoute;

/**
 * Route
 */
class Route extends BaseRoute
{
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
        $this->setOption('filePath', $filePath);

        return $this;
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath()
    {
        return  $this->getOption('filePath');
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
        $this->setOption('fileName', $fileName);

        return $this;
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->getOption('fileName') ?: 'index';
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
        $this->setOption('content', $content);

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

        $this->setOption('list', true);
        $this->setOption('index', $index);
        $this->setOption('order', $order);

        return $this;
    }

    /**
     * Paginate
     *
     * @param string $content
     *
     * @return Route
     */
    public function paginate($content, $index = null, $order = true, $perPage = 10)
    {
        if (!$this->isPaginated()) {
            $this
                ->contents($content, $index, $order)
                ->setPerPage($perPage)
                ->setPath($this->getPath() . '/{page}')
                ->setDefault('page', 1)
                ->setRequirement('page', '\d+');
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
        return $this->getOption('content');
    }

    /**
     * Has content?
     *
     * @return boolean
     */
    public function hasContent()
    {
        return $this->hasOption('content');
    }

    /**
     * Is content list?
     *
     * @return boolean
     */
    public function isList()
    {
        return $this->getOption('list');
    }

    /**
     * Get index by
     *
     * @return string
     */
    public function getIndexBy()
    {
        return $this->getOption('index');
    }

    /**
     * Get sort order
     *
     * @return boolean
     */
    public function getOrder()
    {
        return $this->getOption('order');
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
        $this->setOption('hidden', true);

        return $this;
    }

    /**
     * Is visible?
     *
     * @return boolean
     */
    public function isVisible()
    {
        return !$this->getOption('hidden');
    }

    /**
     * Hide from sitemap
     *
     * @return Route
     */
    public function hideFromSitemap()
    {
        $this->setOption('hide-from-sitemap', true);

        return $this;
    }

    /**
     * Is route on sitemap?
     *
     * @return boolean
     */
    public function isMapped()
    {
        return $this->isVisible() && !$this->getOption('hide-from-sitemap');
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
            ->setDefault('_format', $format)
            ->setRequirement('_format', $format);

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
        $this->setDefault('_template', $template);

        return $this;
    }

    /**
     * Set number of contents per page
     *
     * @param integer $perPage
     *
     * @return Route
     */
    public function setPerPage($perPage)
    {
        $this->setOption('perPage', $perPage);

        return $this;
    }

    /**
     * Get number of contents per page
     *
     * @return integer
     */
    public function getPerPage()
    {
        return $this->getOption('perPage') ?: 10;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->getDefault('_format') ?: 'html';
    }
}
