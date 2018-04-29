<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class InfoField extends AbstractField
{
    const KEY = 'info';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Инфо'];
    }
}
