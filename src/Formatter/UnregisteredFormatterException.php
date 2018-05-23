<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\ParserFactory;

class UnregisteredFormatterException extends \RuntimeException
{
    public function __construct(string $key)
    {
        parent::__construct("Unregistered formatter $key");
    }
}
