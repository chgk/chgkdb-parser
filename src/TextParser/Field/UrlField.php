<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class UrlField extends AbstractField
{
    const KEY = 'url';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['URL'];
    }
}
