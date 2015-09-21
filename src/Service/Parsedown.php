<?php

namespace Phpillip\Service;

use Parsedown as BaseParsedown;
use Phpillip\Service\Pygments;

/**
 * Parsedown
 */
class Parsedown extends BaseParsedown
{
    /**
     * Pygment highlighter
     *
     * @var Pygment
     */
    protected $pygments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pygments = new Pygments();
    }

    /**
     * {@inheritdoc}
     */
    protected function blockCodeComplete($Block)
    {
        $Block['element']['text']['text'] = $this->getCode($Block);

        return $Block;
    }

    /**
     * {@inheritdoc}
     */
    protected function blockFencedCodeComplete($Block)
    {
        $Block['element']['text']['text'] = $this->getCode($Block);

        return $Block;
    }

    /**
     * {@inheritdoc}
     */
    protected function inlineLink($Excerpt)
    {
        $data = parent::inlineLink($Excerpt);

        $data['element']['attributes']['target'] = '_blank';

        return $data;
    }

    /**
     * Process code content
     *
     * @param string $text
     *
     * @return string
     */
    protected function getCode($Block)
    {
        if (!isset($Block['element']['text']['text'])) {
            return null;
        }

        $text = $Block['element']['text']['text'];

        if ($language = $this->getLanguage($Block)) {
            return $this->pygments->highlight($text, $language);
        }

        return htmlspecialchars($text, ENT_NOQUOTES, 'UTF-8');
    }

    /**
     * Get language of the given block
     *
     * @param array $Block
     *
     * @return string
     */
    protected function getLanguage($Block)
    {
        if (!isset($Block['element']['text']['attributes'])) {
            return null;
        }

        return substr($Block['element']['text']['attributes']['class'], strlen('language-'));
    }
}
