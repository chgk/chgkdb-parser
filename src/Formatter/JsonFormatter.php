<?php

namespace Chgk\ChgkDb\Parser\Formatter;

use Chgk\ChgkDb\Parser\Result\Package;
use Chgk\ChgkDb\Parser\Result\Question;
use Chgk\ChgkDb\Parser\Result\Tour;
use Chgk\ChgkDb\Parser\TextParser\Field\AnswerField;
use Chgk\ChgkDb\Parser\TextParser\Field\AuthorField;
use Chgk\ChgkDb\Parser\TextParser\Field\CommentField;
use Chgk\ChgkDb\Parser\TextParser\Field\CopyrightField;
use Chgk\ChgkDb\Parser\TextParser\Field\DateField;
use Chgk\ChgkDb\Parser\TextParser\Field\EditorField;
use Chgk\ChgkDb\Parser\TextParser\Field\InfoField;
use Chgk\ChgkDb\Parser\TextParser\Field\PackageField;
use Chgk\ChgkDb\Parser\TextParser\Field\PassCriteriaField;
use Chgk\ChgkDb\Parser\TextParser\Field\ProcessedByField;
use Chgk\ChgkDb\Parser\TextParser\Field\QuestionField;
use Chgk\ChgkDb\Parser\TextParser\Field\SourceField;
use Chgk\ChgkDb\Parser\TextParser\Field\TourField;
use Chgk\ChgkDb\Parser\TextParser\Field\TypeField;
use Chgk\ChgkDb\Parser\TextParser\Field\UrlField;

class JsonFormatter implements FormatterInterface
{
    const FORMAT_KEY = 'json';

    /**
     * @param Package $package
     * @return string
     * @throws \Chgk\ChgkDb\Parser\Result\Exception\NoFieldException
     */
    public function format(Package $package, string $id = '') : string
    {
        $date = $package->getField(DateField::KEY);
        $parts = $date ? explode(' - ', $date) : [];

        $from = isset($parts[0]) ? date(DATE_ATOM, strtotime($parts[0])): null;
        $to = isset($parts[1]) ? date(DATE_ATOM, strtotime($parts[1])): null;

        $result = [
            'copyright' => (string)$package->getField(CopyrightField::KEY),
            'info' => (string)$package->getField(InfoField::KEY),
            'url' => (string)$package->getField(UrlField::KEY),
            'filename' => "$id.txt",
            'editors' => (string)$package->getField(EditorField::KEY),
            'enteredBy' => (string)$package->getField(ProcessedByField::KEY),
            'updatedAt' => date(DATE_ATOM),
            'playedAt' => $from,
            'finishedAt' => $to,
            'id' => $id,
            'title' => (string)$package->getField(PackageField::KEY),
            'publishedBy' => $package->getPublishedByUserId(),
            'tours' => []
        ];

        $n = 0;
        $type = (string)$package->getField(TypeField::KEY);
        foreach ($package->getTours() as $tour) {
            $result['tours'][] = $this->formatTour(++$n, $id, $tour, $type);
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param int $n
     * @param Tour $tour
     * @return array
     * @throws \Chgk\ChgkDb\Parser\Result\Exception\NoFieldException
     */
    private function formatTour(int $n, $packageId, Tour $tour, $type) {
        $date = (string)$tour->getField(DateField::KEY);
        $parts = $date ? explode(' - ', $date) : [];
        $from = isset($parts[0]) ? date(DATE_ATOM, strtotime($parts[0])): null;
        $to = isset($parts[1]) ? date(DATE_ATOM, strtotime($parts[1])): null;
        $result = [
            'number' => $n,
            'copyright' => (string)$tour->getField(CopyrightField::KEY),
            'info' => (string)$tour->getField(InfoField::KEY),
            'url' => (string)$tour->getField(UrlField::KEY),
            'editors' => (string)$tour->getField(EditorField::KEY),
            'enteredBy' => (string)$tour->getField(ProcessedByField::KEY),
            'updatedAt' => date(DATE_ATOM),
            'playedAt' => $from,
            'finishedAt' => $to,
            'id' => "$packageId.$n",
            'title' => (string)$tour->getField(TourField::KEY),
            'questions' => []
        ];

        foreach ($tour->getQuestions() as $question) {
            $result['questions'][] = $this->formatQuestion($result['id'], $question, $type);
        }

        return $result;
    }

    /**
     * @param $tourId
     * @param Question $question
     * @throws \Chgk\ChgkDb\Parser\Result\Exception\NoFieldException
     */
    private function formatQuestion($tourId, Question $question, $type)
    {
        $number = $question->getField(QuestionField::KEY)->getNumber();
        $result = [
            'id' => "$tourId-".$number,
            'number' => $number,
            'type' => $type ?: 'Ч',
            'question' => (string)$question->getField(QuestionField::KEY),
            'answer' => (string)$question->getField(AnswerField::KEY),
            'passCriteria' => (string)$question->getField(PassCriteriaField::KEY),
            'authors' => (string)$question->getField(AuthorField::KEY),
            'sources' => (string)$question->getField(SourceField::KEY),
            'comments' => (string)$question->getField(CommentField::KEY)
        ];

        return $result;
    }
}
