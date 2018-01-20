<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

class QuizController extends BaseController
{
    /**
     * Get the answer's choice of the current question, used in case the page is reloaded.
     * @return int
     */
    public static function getQuestionChoice()
    {
        if (isset($_SESSION['currentQuestion']) && isset($_SESSION['answer'])) {
            if (array_key_exists($_SESSION['currentQuestion'], $_SESSION['answer'])) {
                return $_SESSION['answer'][$_SESSION['currentQuestion']];
            }
        }

        return 0;
    }

    // TODO: Remove default value of $categoryId later
    /**
     * Generate random questions base on given category id.
     * @param int $categoryId
     * @return array
     */
    public function generateRandomQuestions($categoryId = 1)
    {
        $query = $this->queryHelper->select('id')
                                   ->from('questions')
                                   ->where('category_id = ?')
                                   ->orderBy('RAND()')
                                   ->setQuery()
                                   ->execQuery('getResult', 'i', [$categoryId]);

        $randomQuestions = $this->queryHelper->fetchData($query);
        if (!isset($_SESSION['firstInit'])) {
            $_SESSION['firstInit'] = true;
            foreach ($randomQuestions as $question) {
                $_SESSION['questions'][] = (int)$question->id;
            }
        }

        return $_SESSION['questions'];
    }

    /**
     * Get the latest question that the user left off in case the page is reloaded.
     * @return mixed
     */
    public function getCurrentQuestion()
    {
        $id = (isset($_SESSION['currentQuestion'])) ? $_SESSION['currentQuestion'] : current($_SESSION['questions']);
        return $this->queryHelper->findById('questions', $id);
    }

    /**
     * Return the number of questions
     * @return int
     */
    public function getTotalQuestions()
    {
        return count($_SESSION['questions']);
    }

    /**
     * Get the answer's choice of the question that exists in the answer array.
     * @param $questionId
     * @return int | null
     */
    public function getSelectedChoice($questionId)
    {
        if (array_key_exists($questionId, $_SESSION['answer'])) {
            return $_SESSION['answer'][$questionId];
        }

        return null;
    }
}