<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class PackageField extends AbstractField
{
    const KEY = 'package';

    public function getVariations(): array
    {
        return ['Чемпионат', 'Пакет'];
    }

    public function getIsNumeric(): bool
    {
        return false;
    }

    public function getKey(): string
    {
        return static::KEY;
    }
}
