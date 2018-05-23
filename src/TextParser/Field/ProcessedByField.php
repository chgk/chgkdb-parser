<?php

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class ProcessedByField extends AbstractField
{
    const KEY = 'processed_by';

    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Обработан'];
    }
}
