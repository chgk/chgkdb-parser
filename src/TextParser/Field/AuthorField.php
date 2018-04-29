<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser\Field;

class AuthorField extends AbstractField
{
    const KEY = 'author';
    /**
     * @return string[]
     */
    public function getVariations(): array
    {
        return ['Автор', 'Авторы'];
    }
}
