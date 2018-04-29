<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Exception;

class UnexpectedException extends ParseException
{
    /**
     * UnexpectedException constructor.
     * @param int $lineNumber
     * @param array $expected
     */
    public function __construct(int $lineNumber, array $expected)
    {
        parent::__construct($lineNumber, 'Expected '.implode('|', $expected));
    }
}
