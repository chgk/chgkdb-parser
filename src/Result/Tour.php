<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Result;

class Tour extends AbstractResultItem
{
    /**
     * @var Question[]
     */
    private $questions = [];

    /**
     * @param Question $question
     */
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }

    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }
}

