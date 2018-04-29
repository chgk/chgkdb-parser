<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\ParserFactory;

use Chgk\ChgkDb\Parser\Iterator\ParserIteratorInterface;
use Chgk\ChgkDb\Parser\Result\Package;
use Chgk\ChgkDb\Parser\TextParser\Exception\ParseException;

interface ParserInterface
{
    /**
     * @param ParserIteratorInterface $iterator
     * @return Package
     * @throws ParseException
     */
    public function parse(ParserIteratorInterface $iterator);
}
