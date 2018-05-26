<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Iterator;

use RuntimeException;

class FileLineIterator extends AbstractLineIterator
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
     * FileLineIterator constructor.
     * @param $fileName
     * @param $encoding
     */
    public function __construct($fileName, $encoding = 'utf-8') {
        if (!$this->fileHandle = @fopen($fileName, 'r')) {
            throw new RuntimeException('Can not open file "' . $fileName . '"');
        }
        parent::__construct($encoding);
    }

    public function __destruct() {
        fclose($this->fileHandle);
    }

    protected function innerRewind()
    {
        fseek($this->fileHandle, 0);
    }

    protected function innerNext()
    {
        $this->line = rtrim(fgets($this->fileHandle));
    }
}
