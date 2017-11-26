<?php
/**
 * @author: Tuan Nguyen
 */

require_once DRIVER_DIR . '/ModelHelper.php';

class BaseController
{
    public $modelHelper;
    public $currentUser;

    public function __construct(ModelHelper $modelHelper)
    {
        $this->modelHelper = $modelHelper;
    }

    public static function getRequestParams()
    {
        $jsonData = file_get_contents("php://input");
        return json_decode($jsonData);
    }

    public function toInteger($value)
    {
        return (int)$value;
    }

    public function toObject($arr)
    {
        return json_decode(json_encode($arr));
    }

    public function getUrlParams()
    {
        return $this->toObject($_GET);
    }

    public function jsonResponse($data = [])
    {
        return json_encode($data);
    }

    public static function getQuestionChoice()
    {
        if (isset($_SESSION['currentQuestion']) && isset($_SESSION['answer'])) {
            if (array_key_exists($_SESSION['currentQuestion'], $_SESSION['answer'])) {
                $selectedChoice = $_SESSION['answer'][$_SESSION['currentQuestion']];
            } else {
                $selectedChoice = 0;
            }
            return $selectedChoice;
        } else {
            return 0;
        }
    }

    public function generateRandomQuestions()
    {
        $randomQuestions = $this->modelHelper
            ->select(['id'])
            ->from('questions')
            ->where([
                'category_id=' => 1
            ])
            ->orderBy(['RAND()'])
            ->method('many');
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
        $firstQuestion = $this->modelHelper
            ->select()
            ->from('questions')
            ->where([
                'id=' => (isset($_SESSION['currentQuestion'])) ? $_SESSION['currentQuestion'] : current($_SESSION['questions'])
            ])
            ->method('one');
        return $this->toObject($firstQuestion);
    }

    public function getTotalQuestions()
    {
        return count($_SESSION['questions']);
    }

    public function loginAuth($username, $password)
    {
        $numOfRow = $this->modelHelper
            ->select()
            ->from('users')
            ->where([
                'username =' => $username,
                'password =' => $password
            ])
            ->method('numRows');
        $userInfo = $this->modelHelper
            ->select()
            ->from('users')
            ->where([
                'username =' => $username,
                'password =' => $password
            ])
            ->method('one');

        $_SESSION['user'] = ($numOfRow == 1) ? $userInfo : [];
        return $_SESSION['user'];
    }

    public function getUserInfo($table, $field = ['*'])
    {
        $this->currentUser = $this->modelHelper->findById($table, $_SESSION['user']['id'], $field);
        return $this->toObject($this->currentUser);
    }

    public static function hasSignedIn()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
    }

    public static function logout()
    {
        if (self::hasSignedIn()) {
            unset($_SESSION['user']);
            header('Location: index.php');
        } else {
            header('Location: ?page=login');
        }
    }

}