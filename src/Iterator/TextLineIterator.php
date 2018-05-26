<?php

namespace Chgk\ChgkDb\Parser\Iterator;

class TextLineIterator extends AbstractLineIterator
{
    /**
     * @var string[]
     */
    private $lines;

    /**
     * FileLineIterator constructor.
     * @param $text
     * @param string $encoding
     */
    public function __construct($text, $encoding = 'utf-8') {
        $this->lines = explode("\n", $text);
        parent::__construct($encoding);
    }

    protected function innerNext()
    {
        $this->line = isset($this->lines[$this->key()+1]) ? $this->lines[$this->key()+1] : false;
    }
}
