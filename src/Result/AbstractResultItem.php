<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Result;

use Chgk\ChgkDb\Parser\Result\Exception\NoFieldException;
use Chgk\ChgkDb\Parser\TextParser\Field\FieldInterface;

class AbstractResultItem
{
    /**
     * @var FieldInterface[]
     */
    private $fields = [];

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FieldInterface[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param string $key
     * @return FieldInterface
     * @throws NoFieldException
     */
    public function getField(string $key)
    {
        if (!isset($this->fields[$key])) {
            return '';
        }
        return $this->fields[$key];
    }

    public function addField(FieldInterface $field)
    {
        $this->fields[$field->getKey()] = $field;
    }
}
