<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class SourceField extends AbstractField
{
    const KEY = 'source';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Источник', 'Источники'];
    }
}
