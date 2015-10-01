<?php

namespace Phpillip\Twig;

use Phpillip\Service\Parsedown;
use Twig_Extension as Extension;
use Twig_SimpleFilter as SimpleFilter;

/**
 * Markdown extension
 */
class MarkdownExtension extends Extension
{
    /**
     * Markdown parser
     *
     * @var Parsdown
     */
    protected $parser;

    /**
     * Constructor
     *
     * @param Parsedown $parser
     */
    public function __construct(Parsedown $parser)
    {
        $this->parser = $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new SimpleFilter('markdown', [$this, 'markdownify']),
        ];
    }

    /**
     * Parse Mardown to return HTML
     *
     * @param string $data
     *
     * @return string
     */
    public function markdownify($data)
    {
        return $this->parser->parse($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'markdown_extension';
    }
}
