<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class EditorField extends AbstractField
{
    const KEY = 'editor';

   /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Редактор', 'Редакторы'];
    }
}
