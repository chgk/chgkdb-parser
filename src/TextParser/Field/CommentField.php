<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class CommentField extends AbstractField
{
    const KEY = 'comment';

    public function getVariations(): array
    {
        return ['Комментарий', 'Комментарии'];
    }
}
