<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Result;

class Package extends AbstractResultItem
{
    /**
     * @var Tour[]
     */
    private $tours = [];

    private $isSingleTour = false;

    /**
     * @var int|null
     */
    private $publishedByUserId = null;

    /**
     * @return Tour[]
     */
    public function getTours(): array
    {
        return $this->tours;
    }

    /**
     * @param Tour[] $tours
     */
    public function setTours(array $tours): void
    {
        $this->tours = $tours;
    }

    public function addTour(Tour $tour)
    {
        $this->tours[] = $tour;
    }

    public function markAsSingleTour()
    {
        $this->isSingleTour = true;
    }

    /**
     * @return int|null
     */
    public function getPublishedByUserId(): ?int
    {
        return $this->publishedByUserId;
    }

    /**
     * @param int|null $publishedByUserId
     */
    public function setPublishedByUserId(?int $publishedByUserId): void
    {
        $this->publishedByUserId = $publishedByUserId;
    }
}
