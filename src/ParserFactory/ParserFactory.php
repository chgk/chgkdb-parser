<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\ParserFactory;

use Chgk\ChgkDb\Parser\TextParser\TextParser;

class ParserFactory
{
    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    public function __construct()
    {
        $this->registerParser(TextParser::FORMAT_KEY, new TextParser());
    }

    public function registerParser(string $key, ParserInterface $parser)
    {
        $this->parsers[$key] = $parser;
    }

    /**
     * @param string $key
     * @return ParserInterface
     * @throws UnregisteredParserException
     */
    public function getParser(string $key) : ParserInterface
    {
        if (!isset($this->parsers[$key])) {
            throw new UnregisteredParserException($key);
        }

        return $this->parsers[$key];
    }
}
