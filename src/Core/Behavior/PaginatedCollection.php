<?php


namespace App\Core\Behavior;


class PaginatedCollection
{
    /**
     * @var iterable
     */
    private $collection;
    /**
     * @var int
     */
    private $currentPage;
    /**
     * @var int
     */
    private $nbPage;
    /**
     * @var int
     */
    private $nbResults;

    public function __construct(iterable $data, int $currentPage, int $nbPage, int $nbResults)
    {
        $this->collection = $data;
        $this->currentPage = $currentPage;
        $this->nbPage = $nbPage;
        $this->nbResults = $nbResults;
    }

    /**
     * @return iterable
     */
    public function getCollection(): iterable
    {
        return $this->collection;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getNbPage(): int
    {
        return $this->nbPage;
    }

    /**
     * @return int
     */
    public function getNbResults(): int
    {
        return $this->nbResults;
    }


}