<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class QuestionField extends AbstractField
{
    const KEY = 'question';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Вопрос'];
    }

    /**
     * @return bool
     */
    public function getIsNumeric(): bool
    {
        return true;
    }
}
