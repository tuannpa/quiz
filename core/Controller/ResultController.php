<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';
require_once HELPER_DIR . 'Paginator.php';

class ResultController extends BaseController
{
    /**
     * ResultController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int $currentPage
     * @param int $itemPerPage
     * @return array
     */
    public function getResult($currentPage, $itemPerPage)
    {
        $totalItems = $this->queryHelper->all('quiz_result', true);
        $paginator = new Paginator($itemPerPage, $totalItems);
        $pagingOptions = $paginator->getPagingOptions($currentPage);
        $result = $this->queryHelper
            ->select('u.name', 'u.username', 'q.correct_answer', 'q.incorrect_answer', 'q.score', 'q.finish_time', 'q.created_at')
            ->from('quiz_result AS q')
            ->join('users AS u', 'INNER')
            ->on('q.user_id = u.id')
            ->orderBy('q.id')
            ->limit($itemPerPage, $pagingOptions['offset'])
            ->setQuery()
            ->execQuery('getResult');

        return [
            'result' => $this->queryHelper->fetchData($result),
            'pagingOptions' => $pagingOptions
        ];
    }

    /**
     * Split the date and time.
     * @param string $timestamp
     * @param string $format
     * @return array
     */
    public function transformDateTime($timestamp, $format = 'd/m/Y H:i:s')
    {
        $dateTime = date($format, strtotime($timestamp));
        $seperated = explode(' ', $dateTime);

        return [
            'date' => $seperated[0],
            'time' => $seperated[1]
        ];
    }
}