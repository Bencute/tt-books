<?php


namespace system\helper;


class Pagination
{
    /**
     * @var int
     */
    public int $count;

    /**
     * @var int
     */
    public int $perPage;

    /**
     * @var int
     */
    public int $currentPage;

    /**
     * Pagination constructor.
     * @param int $currentPage
     * @param int $count
     * @param int $perPage
     */
    public function __construct(int $currentPage, int $count, int $perPage)
    {
        $this->count = $count;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
    }

    public function getCountPage(): int
    {
        return ceil($this->count / $this->perPage);
    }

    public function getOffset(int $offsetPage = 0): int
    {
        return ($this->currentPage - 1 + $offsetPage) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }
}