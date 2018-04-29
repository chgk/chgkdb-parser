<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser;

use Chgk\ChgkDb\Parser\TextParser\Exception\UnknownFieldException;
use Chgk\ChgkDb\Parser\TextParser\Field\AnswerField;
use Chgk\ChgkDb\Parser\TextParser\Field\AuthorField;
use Chgk\ChgkDb\Parser\TextParser\Field\CommentField;
use Chgk\ChgkDb\Parser\TextParser\Field\CopyrightField;
use Chgk\ChgkDb\Parser\TextParser\Field\DateField;
use Chgk\ChgkDb\Parser\TextParser\Field\EditorField;
use Chgk\ChgkDb\Parser\TextParser\Field\FieldInterface;
use Chgk\ChgkDb\Parser\TextParser\Field\InfoField;
use Chgk\ChgkDb\Parser\TextParser\Field\PackageField;
use Chgk\ChgkDb\Parser\TextParser\Field\PassCriteriaField;
use Chgk\ChgkDb\Parser\TextParser\Field\QuestionField;
use Chgk\ChgkDb\Parser\TextParser\Field\RatingField;
use Chgk\ChgkDb\Parser\TextParser\Field\SourceField;
use Chgk\ChgkDb\Parser\TextParser\Field\TourField;
use Chgk\ChgkDb\Parser\TextParser\Field\TypeField;
use Chgk\ChgkDb\Parser\TextParser\Field\UrlField;

class FieldFactory
{
    private $registeredFieldClasses = [
        PackageField::KEY => PackageField::class,
        DateField::KEY => DateField::class,
        TourField::KEY => TourField::class,
        QuestionField::KEY => QuestionField::class,
        UrlField::KEY => UrlField::class,
        InfoField::KEY => InfoField::class,
        EditorField::KEY => EditorField::class,
        AnswerField::KEY => AnswerField::class,
        SourceField::KEY => SourceField::class,
        CommentField::KEY => CommentField::class,
        AuthorField::KEY => AuthorField::class,
        PassCriteriaField::KEY => PassCriteriaField::class,
        TypeField::KEY => TypeField::class,
        CopyrightField::KEY => CopyrightField::class,
        RatingField::KEY => RatingField::class
    ];


    /**
     * @param string $field
     * @return FieldInterface
     * @throws UnknownFieldException
     */
    public function getField(string $field)
    {
        if (!isset($this->registeredFieldClasses[$field])) {
            throw new UnknownFieldException($field);
        }
        $class = $this->registeredFieldClasses[$field];

        return new $class;
    }

    public function getStateFields(string $state)
    {
        if ($state === ParserState::STATE_START) {
            return [PackageField::KEY];
        }

        if ($state === ParserState::STATE_PACKAGE) {
            return [
                DateField::KEY,
                TourField::KEY,
                UrlField::KEY,
                InfoField::KEY,
                EditorField::KEY,
                AuthorField::KEY,
                TypeField::KEY,
                QuestionField::KEY,
                CopyrightField::KEY,
            ];
        }

        if ($state === ParserState::STATE_QUESTION) {
            return [
                AnswerField::KEY,
                SourceField::KEY,
                CommentField::KEY,
                AuthorField::KEY,
                PassCriteriaField::KEY,
                QuestionField::KEY,
                TourField::KEY,
                TypeField::KEY,
                CopyrightField::KEY,
                RatingField::KEY
            ];
        }

        if ($state === ParserState::STATE_TOUR) {
            return [
                DateField::KEY,
                TourField::KEY,
                UrlField::KEY,
                InfoField::KEY,
                EditorField::KEY,
                QuestionField::KEY,
                AuthorField::KEY,
                TypeField::KEY,
            ];
        }

        throw new \InvalidArgumentException('Unknown state: '.$state);
    }
}
