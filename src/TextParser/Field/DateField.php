<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

use Chgk\ChgkDb\Parser\TextParser\Exception\InvalidFieldValue;

class DateField extends AbstractField
{
    const KEY = 'date';

    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Дата'];
    }

    /**
     * @return bool
     */
    public function getIsNumeric(): bool
    {
        return false;
    }

    public function addToContent($string): void
    {
        $strings = preg_split('/\s+-+\s+/', $string);
        foreach ($strings as &$s) {
            if (preg_match('/00-000-(\d{4})/', $s, $maches)) {
                $s = $maches[1];
                continue;
            }
            $t = strtotime($s);
            if (!$t) {
                throw new InvalidFieldValue('Invalid date: '.$s);
            }
            $s = date('d.m.Y', $t);
        }
        parent::addToContent(implode(' - ', $strings));
    }
}
