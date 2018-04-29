<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class AnswerField extends AbstractField
{
    const KEY = 'answer';

    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Ответ'];
    }
}
