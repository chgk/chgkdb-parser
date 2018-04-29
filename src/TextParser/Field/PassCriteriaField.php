<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class PassCriteriaField extends AbstractField
{
    const KEY = 'pass_criteria';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Зачет', 'Зачёт'];
    }
}
