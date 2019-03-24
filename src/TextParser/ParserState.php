<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\TextParser;

use Chgk\ChgkDb\Parser\Result\Package;
use Chgk\ChgkDb\Parser\Result\Question;
use Chgk\ChgkDb\Parser\Result\Tour;
use Chgk\ChgkDb\Parser\TextParser\Exception\InvalidFieldException;
use Chgk\ChgkDb\Parser\TextParser\Field\FieldInterface;
use Chgk\ChgkDb\Parser\TextParser\Field\PackageField;
use Chgk\ChgkDb\Parser\TextParser\Field\QuestionField;
use Chgk\ChgkDb\Parser\TextParser\Field\TourField;

class ParserState
{
    const STATE_START = 'start';

    const STATE_PARSING_FIELD = 'parsing_field';

    const STATE_PACKAGE = 'package';

    const STATE_TOUR = 'tour';

    const STATE_QUESTION = 'question';

    /**
     * @var string
     */

    private $packageName;

    /**
     * @var FieldInterface
     */
    private $currentField;

    private $currentFieldNumber;

    private $stateStack = [self::STATE_START];

    /**
     * @var Package
     */
    private $package;

    /**
     * @var Tour
     */
    private $currentTour;

    /**
     * @var Question|null
     */
    private $currentQuestion;

    /**
     * @var int[]
     */
    private $currentTourQuestionNumbers = [];

    /**
     * @param string $packageName
     */
    public function setPackageName(string $packageName): void
    {
        $this->packageName = $packageName;
    }

    /**
     * @param FieldInterface $currentField
     * @throws InvalidFieldException
     */
    public function setCurrentField(FieldInterface $currentField): void
    {
        $state = $this->getState();
        $fieldKey = $currentField->getKey();

        $this->validateNumber($currentField);

        if ($this->currentQuestion && in_array($fieldKey, [QuestionField::KEY, TourField::KEY])) {
            $this->saveQuestion();
        }

        if ($this->currentTour && TourField::KEY === $fieldKey){
            $this->saveTour();
        }

        if (self::STATE_PACKAGE === $state && QuestionField::KEY === $fieldKey ) {
            $this->startTour();
            $this->package->markAsSingleTour();
        }

        if (TourField::KEY === $fieldKey) {
            $this->setState(self::STATE_TOUR);
            $this->startTour();
        } elseif (QuestionField::KEY === $fieldKey) {
            $this->setState(self::STATE_QUESTION);
            $this->currentQuestion = new Question();
        } elseif (PackageField::KEY === $fieldKey) {
            $this->setState(self::STATE_PACKAGE);
            $this->package = new Package();
        }

        $this->currentField = $currentField;

        $this->setState(self::STATE_PARSING_FIELD);
    }

    public function finish() : void
    {
        if ($this->currentField) {
            $this->saveField();
        }
        if ($this->currentQuestion) {
            $this->saveQuestion();
        }

        if ($this->currentTour) {
            $this->saveTour();
        }
    }

    /**
     * @param mixed $currentFieldNumber
     */
    public function setCurrentFieldNumber($currentFieldNumber): void
    {
        $this->currentFieldNumber = $currentFieldNumber;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return end($this->stateStack);
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->stateStack[] = $state;
    }

    /**
     * @param string $s
     * @throws Exception\InvalidFieldValue
     */
    public function addToCurrentField(string $s)
    {
        $this->currentField->addToContent($s);
    }

    /**
     * @return int
     */
    public function getCurrentFieldKey()
    {
        return $this->currentField->getKey();
    }

    public function saveField()
    {
        $this->popState();
        $state = $this->getState();

        if (self::STATE_QUESTION === $state) {
            $this->currentQuestion->addField($this->currentField);
        }
        if (self::STATE_PACKAGE === $state) {
            $this->package->addField($this->currentField);
        }
        if (self::STATE_TOUR === $state) {
            $this->currentTour->addField($this->currentField);
        }
        $this->currentField = null;
    }

    private function popState()
    {
        array_pop($this->stateStack);
    }

    private function saveQuestion(): void
    {
        $this->currentTour->addQuestion($this->currentQuestion);
        $this->currentQuestion = null;
        $this->popState();
    }

    private function saveTour(): void
    {
        $this->package->addTour($this->currentTour);
        $this->currentTour = null;
        $this->popState();
    }

    /**
     * @return Package
     */
    public function getPackage(): Package
    {
        return $this->package;
    }

    private function startTour(): void
    {
        $this->currentTour = new Tour();
        $this->currentTourQuestionNumbers = [];
    }

    /**
     * @param FieldInterface $currentField
     * @throws InvalidFieldException
     */
    private function validateNumber(FieldInterface $currentField): void
    {
        if ($currentField->getKey() !== QuestionField::KEY) {
            return;
        }

        $number = $currentField->getNumber();
        if (in_array($number, $this->currentTourQuestionNumbers)) {
            throw new InvalidFieldException(sprintf('Number %d is already used', $number));
        }
        $this->currentTourQuestionNumbers[] = $number;
    }
}
