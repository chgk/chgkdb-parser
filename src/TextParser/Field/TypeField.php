<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class TypeField extends AbstractField
{
    const KEY = 'type';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Вид'];
    }
}
