<?php

namespace Chgk\ChgkDb\Parser\Iterator;

abstract class AbstractLineIterator implements ParserIteratorInterface
{
    /**
     * @var resource
     */
    protected $fileHandle;

    /**
     * @var string|bool
     */
    protected $line;

    /**
     * @var int
     */
    protected $i;

    /**
     * @var string
     */
    private $encoding;

    /**
     * AbstractLineIterator constructor.
     * @param string $encoding
     */
    public function __construct(string $encoding = 'utf-8')
    {
        $this->encoding = $encoding;
        $this->i = -1;
    }

    public function rewind()
    {
        $this->i = -1;
        $this->innerRewind();
        $this->next();
    }

    protected function innerRewind()
    {

    }

    public function next()
    {
        $this->innerNext();
        $this->i++;
        if ($this->line ===  false) {
            return;
        }
        if ($this->encoding !== 'utf-8') {
            $this->line = iconv($this->encoding, 'utf-8', $this->line);
        }
        $this->line = html_entity_decode($this->line);
    }

    abstract protected function innerNext();

    public function valid()
    {
        return false !== $this->line;
    }

    /**
     * @return string
     */
    public function current()
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->i;
    }
}
