<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

abstract class AbstractField implements FieldInterface
{
    const KEY = '';
    /**
     * @var string
     */
    private $content = '';

    /** @var int */
    private $number;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return rtrim($this->content);
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getKey(): string
    {
        return static::KEY;
    }

    /**
     * @return bool
     */
    public function getIsNumeric(): bool
    {
        return false;
    }

    public function addToContent($string): void
    {
        $this->content.=$string."\n";
    }

    public function __toString()
    {
        return $this->getContent();
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}
