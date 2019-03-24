<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser;

use Chgk\ChgkDb\Parser\Iterator\ParserIteratorInterface;
use Chgk\ChgkDb\Parser\ParserFactory\ParserInterface;
use Chgk\ChgkDb\Parser\Result\Package;
use Chgk\ChgkDb\Parser\TextParser\Exception\InvalidFieldException;
use Chgk\ChgkDb\Parser\TextParser\Exception\InvalidFieldValue;
use Chgk\ChgkDb\Parser\TextParser\Exception\ParseException;
use Chgk\ChgkDb\Parser\TextParser\Exception\UnexpectedException;
use Chgk\ChgkDb\Parser\TextParser\Field\FieldInterface;

class TextParser implements ParserInterface
{
    const FORMAT_KEY = 'text';

    /**
     * @var FieldFactory
     */
    private $fieldFactory;

    /**
     * Parser constructor.
     */
    public function __construct()
    {
        $this->fieldFactory = new FieldFactory();
    }

    /**
     * @param ParserIteratorInterface $iterator
     * @return Package
     * @throws ParseException
     */
    public function parse(ParserIteratorInterface $iterator) :Package
    {
        $state = new ParserState();
        $iterator->next();

        while ($iterator->valid()) {
            $this->parseCurrentLine($iterator, $state);
            $iterator->next();
        }
        $state->finish();

        return $state->getPackage();
    }

    /**
     * @param ParserIteratorInterface $iterator
     * @param ParserState $stateObject
     * @throws ParseException
     */
    private function parseCurrentLine(ParserIteratorInterface $iterator, ParserState $stateObject)
    {
        $state = $stateObject->getState();

        if ($state === ParserState::STATE_PARSING_FIELD) {
            if ($this->isCurrentLineEmpty($iterator)) {
                $stateObject->saveField();
                return;
            }
            try {
                $stateObject->addToCurrentField($iterator->current());
            } catch (InvalidFieldValue $e) {
                throw new ParseException($iterator->key(), $e->getMessage());
            }

            return;
        }

        if ($this->isCurrentLineEmpty($iterator)) {
            return;
        }

        $allowedFieldKeys = $this->fieldFactory->getStateFields($state);

        /** @var FieldInterface $field */
        list($line, $field, $number) = $this->stripFieldName($iterator, $allowedFieldKeys);
        if ($field->getIsNumeric()) {
            $field->setNumber($number);
        }

        try {
            $stateObject->setCurrentField($field);
        } catch (InvalidFieldException $e) {
            throw new ParseException($iterator->key(), $e->getMessage());
        }
        if ($line) {
            try {
                $stateObject->addToCurrentField($line);
            } catch (InvalidFieldValue $e) {
                throw new ParseException($iterator->key(), $e->getMessage());
            }
        }
    }

    /**
     * @param ParserIteratorInterface $iterator
     * @param string[]
     * @return array [line, field, number]
     * @throws ParseException
     */
    private function stripFieldName($iterator, $fieldKeys)
    {
        $vars = [];
        foreach ($fieldKeys as $fieldKey) {
            $field = $this->fieldFactory->getField($fieldKey);
            $numericPart = $field->getIsNumeric() ? '\s+(\d+)' : '';
            foreach ($field->getVariations() as $variation) {
                $vars[] = $variation;
                if (preg_match("/({$variation}){$numericPart}[.:]\s*(.*)$/", $iterator->current(), $maches)) {
                    return $field->getIsNumeric() ? [$maches[3], $field, (int)$maches[2]] : [$maches[2], $field, 0];
                }
            }
        }
        throw new UnexpectedException($iterator->key(), $vars);
    }

    private function isCurrentLineEmpty(ParserIteratorInterface $iterator)
    {
        return preg_match('/^\s*$/', $iterator->current());
    }
}
