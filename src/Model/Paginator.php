<?php

namespace Phpillip\Model;

use Countable;
use Iterator;
use RuntimeException;

/**
 * A simple Paginator
 */
class Paginator implements Iterator, Countable
{
    /**
     * Pages
     *
     * @var array
     */
    protected $pages;

    /**
     * Position
     *
     * @var integer
     */
    protected $position;

    /**
     * Constructor
     *
     * @param array $contents
     * @param integer $perPage
     */
    public function __construct(array $contents, $perPage = 10)
    {
        $this->pages = array_chunk($contents, $perPage);
    }

    /**
     * Get contents for the given page
     *
     * @param integer $page
     *
     * @return array
     */
    public function get($page = 1)
    {
        $index = $page - 1;

        if (!isset($this->pages[$index])) {
            throw new RuntimeException(sprintf('Invalid page %s of %s', $page, $this->count()));
        }

        return $this->pages[$index];
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
        return $this->pages[$this->position];
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
        return isset($this->pages[$this->position]);
    }

    /**
     * Get number of pages fo the given contents
     *
     * @return integer
     */
    public function count()
    {
        return count($this->pages);
    }
}
