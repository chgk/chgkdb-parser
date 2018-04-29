<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

use Chgk\ChgkDb\Parser\TextParser\Exception\InvalidFieldValue;

interface FieldInterface
{
    /**
     * @return string[]
     */
    public function getVariations(): array;

    /**
     * @return bool
     */
    public function getIsNumeric(): bool ;

    /**
     * @return string
     */
    public function getKey(): string ;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @param string $content
     */
    public function setContent(string $content): void;

    /**
     * @param $string
     * @throws InvalidFieldValue
     */
    public function addToContent($string): void;

    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @param int $number
     */
    public function setNumber(int $number): void;

}
