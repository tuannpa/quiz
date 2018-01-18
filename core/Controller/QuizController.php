<?php

require_once CONTROLLER_DIR . 'Base/BaseController.php';

class QuizController extends BaseController
{
    public static function getQuestionChoice()
    {
        if (isset($_SESSION['currentQuestion']) && isset($_SESSION['answer'])) {
            if (array_key_exists($_SESSION['currentQuestion'], $_SESSION['answer'])) {
                $selectedChoice = $_SESSION['answer'][$_SESSION['currentQuestion']];
            } else {
                $selectedChoice = 0;
            }
            return $selectedChoice;
        }

        return 0;
    }

    // TODO: Remove default value of $categoryId later
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
                $_SESSION['questions'][] = $this->toInteger($question['id']);
            }
        }

        return $_SESSION['questions'];
    }

    public function getFirstQuestion()
    {
        $id = (isset($_SESSION['currentQuestion'])) ? $_SESSION['currentQuestion'] : current($_SESSION['questions']);
        return $this->queryHelper->findById('questions', $id);
    }

    public function getTotalQuestions()
    {
        return count($_SESSION['questions']);
    }
}