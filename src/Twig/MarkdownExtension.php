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
        $parser = new Parsedown();

        return $parser->parse($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'markdown_extension';
    }
}
