<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Exception;

class ParseException extends \Exception
{
    /**
     * @var int
     */
    private $lineNumber;

    /**
     * ParseException constructor.
     * @param int $lineNumber
     * @param string $message
     */
    public function __construct(int $lineNumber, string $message = "")
    {
        parent::__construct($message.' at line '.($lineNumber));
        $this->lineNumber = $lineNumber;
    }

    /**
     * @return int
     */
    public function getLineNumber() : int
    {
        return $this->lineNumber;
    }
}
