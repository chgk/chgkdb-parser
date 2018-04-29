<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class TourField extends AbstractField
{
    const KEY = 'tour';
    /**
     * @return array
     */
    public function getVariations(): array
    {
        return ['Тур'];
    }

    /**
     * @return bool
     */
    public function getIsNumeric(): bool
    {
        return false;
    }
}
