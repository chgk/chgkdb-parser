<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Exception;

class UnknownFieldException extends \LogicException
{
    /**
     * UnknownFieldException constructor.
     * @param string $fieldKey
     */
    public function __construct(string $fieldKey)
    {
        parent::__construct("Unknown field $fieldKey");
    }
}
