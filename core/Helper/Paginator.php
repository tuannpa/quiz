<?php

class Paginator
{
    /**
     * @var int
     */
    public $itemPerPage;

    /**
     * @var int
     */
    public $totalItems;

    /**
     * @var int
     */
    public $pageLimit = 10;

    /**
     * Paginator constructor.
     * @param int $itemPerPage
     * @param int $totalItems
     */
    public function __construct($itemPerPage, $totalItems)
    {
        $this->itemPerPage = $itemPerPage;
        $this->totalItems = $totalItems;
    }

    /**
     * Get the calculated paging options.
     * @param int $currentPage
     * @return array
     */
    public function getPagingOptions($currentPage)
    {
        $offset = ($currentPage - 1) * $this->itemPerPage;
        $totalPages = ceil($this->totalItems / $this->itemPerPage);

        if (($this->pageLimit - $currentPage) < 4) {
            $startPage = $currentPage - 5;
            $endPage = $currentPage + 4;
            if ($endPage > $totalPages) {
                $endPage = $totalPages;
            }
        } else {
            $startPage = 1;
            $endPage = $this->pageLimit;
        }

        var_dump($startPage, $endPage);

        return [
            'totalPages' => ceil($this->totalItems / $this->itemPerPage),
            'offset' => $offset,
            'start' => ++$offset,
            'end' => min(($offset + $this->itemPerPage), $this->totalItems)
        ];
    }

    public function loadTemplate($currentPage)
    {

    }
}