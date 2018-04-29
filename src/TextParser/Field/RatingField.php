<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class RatingField extends AbstractField
{
    const KEY = 'rating';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Рейтинг'];
    }
}
