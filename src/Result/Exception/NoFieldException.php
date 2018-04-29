<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Result\Exception;

class NoFieldException extends \Exception
{
    public function __construct($key)
    {
        parent::__construct("Field $key is not set");
    }
}
