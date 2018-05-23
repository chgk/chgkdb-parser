<?php

namespace Chgk\ChgkDb\Parser\Formatter;

use Chgk\ChgkDb\Parser\ParserFactory\UnregisteredFormatterException;

class FormatterFactory
{
    /**
     * @var FormatterInterface[]
     */
    private $formatters = [];

    public function __construct()
    {
        $this->registerParser(HtmlFormatter::FORMAT_KEY, HtmlFormatter::create());
        $this->registerParser(JsonFormatter::FORMAT_KEY, new JsonFormatter());

    }

    public function registerParser(string $key, FormatterInterface $formatter)
    {
        $this->formatters[$key] = $formatter;
    }

    /**
     * @param string $key
     * @return FormatterInterface
     */
    public function getParser(string $key) : FormatterInterface
    {
        if (!isset($this->formatters[$key])) {
            throw new UnregisteredFormatterException($key);
        }

        return $this->formatters[$key];
    }
}
