<?php

namespace Phpillip\Model;

use Iterator;
use Countable;

/**
 * Sitemap
 */
class Sitemap implements Iterator, Countable
{
    /**
     * Urls
     *
     * @var array
     */
    protected $urls = [];

    /**
     * Position
     *
     * @var integer
     */
    protected $position;

    /**
     * Add location
     *
     * @param string $location
     * @param DateTime $lastModified
     * @param integer $priority
     * @param string $frequency
     */
    public function add($location, $lastModified = null, $priority = null, $frequency = null)
    {
        $url = ['location' => $location];

        if ($priority === null && empty($this->urls)) {
            $priority = 0;
        }

        if ($lastModified) {
            $url['lastModified'] = $lastModified;
        }

        if ($priority !== null) {
            $url['priority'] = $priority;
        }

        if ($frequency) {
            $url['frequency'] = $frequency;
        }

        $this->urls[] = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->urls[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return isset($this->urls[$this->position]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->urls);
    }
}
