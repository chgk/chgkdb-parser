<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class CopyrightField extends AbstractField
{
    const KEY = 'copyright';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Копирайт'];
    }
}
