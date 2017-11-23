<?php
session_start();
require_once '../ajaxConfig.php';
require_once HELPER_DIR . '/TemplateHelper.php';
$params = BaseController::getRequestParams();
$controller = new BaseController(new ModelHelper());
$request = $controller->getUrlParams();

if (!isset($_SESSION['answer'])) {
    $_SESSION['answer'] = [];
}

$_SESSION['answer'][$params->currentQuestionId] = $params->userChoice;

foreach ($_SESSION['questions'] as $key => $questionId) {
    if ($request->task == 'prev') {
        if ($params->currentQuestionId == $questionId) {
            (($key - 1) != 0) ? $showBtnPrev = true : $showBtnPrev = false;
            $prevQuestionId = $_SESSION['questions'][$key - 1];
            $position = $key;
        }
    } else {
        if ($params->currentQuestionId == $questionId) {
            ($key >= 0) ? $showBtnPrev = true : $showBtnPrev = false;
            ($key == (count($_SESSION['questions']) - 1)) ? $_SESSION['endOfTest'] = true : null;
            ($key == (count($_SESSION['questions']) - 1)) ? $totalTime = $params->totalTime : null;
            $nextQuestionId = (!isset($_SESSION['endOfTest'])) ? $_SESSION['questions'][$key + 1] : null;
            $position = (!isset($_SESSION['endOfTest'])) ? $key + 2 : count($_SESSION['questions']);
            $showBtnBackToHome = (isset($_SESSION['endOfTest'])) ? $showBtnBackToHome = true : null;
            if (isset($_SESSION['endOfTest'])) {
                $showBtnPrev = false;
            }
        }
    }
}

$_SESSION['currentQuestion'] = (isset($nextQuestionId)) ? $nextQuestionId : $prevQuestionId;

if (!isset($_SESSION['endOfTest'])) {
    $question = $controller->modelHelper
        ->select()
        ->from('questions')
        ->where([
            'id=' => isset($nextQuestionId) ? $nextQuestionId : $prevQuestionId
        ])
        ->method('one');
    $question = $controller->toObject($question);

    $questionTemplate = TemplateHelper::setFilePath('template/questionTemplate.html')
        ->renderTemplate([
            'questionId' => $question->id,
            'questionContent' => $question->content,
            'firstChoice' => $question->first_choice,
            'secondChoice' => $question->second_choice,
            'thirdChoice' => $question->third_choice,
            'fourthChoice' => $question->fourth_choice
        ]);

    $questionTrackingTemplate = TemplateHelper::setFilePath('template/questionTrackingTemplate.html')
        ->renderTemplate([
            'position' => $position,
            'totalQuestions' => $params->totalQuestions
        ]);
} else {
    $allQuestions = $controller->modelHelper
        ->select([
            'id', 'answer'
        ])
        ->from('questions')
        ->where([
            'category_id=' => 1
        ])
        ->method('many');

    $allQuestions = $controller->toObject($allQuestions);
    $correctAnswer = 0;
    $incorrectAnswer = 0;
    foreach ($allQuestions as $question) {
        if (array_key_exists($question->id, $_SESSION['answer'])) {
            if ($_SESSION['answer'][$question->id] == $question->answer) {
                $correctAnswer++;
            } else {
                $incorrectAnswer++;
            }
        }
    }
    $score = $correctAnswer * 0.5;
    $controller->modelHelper
        ->insert('quiz_result', [
            'user_id' => $_SESSION['user']['id'],
            'correct_answer' => $correctAnswer,
            'incorrect_answer' => $incorrectAnswer,
            'score' => $score,
            'finish_time' => $totalTime
        ])
        ->method('crud');
    $endOfTestTemplate = TemplateHelper::setFilePath('template/endOfTestTemplate.html')
        ->renderTemplate([
            'correctAnswer' => $correctAnswer,
            'incorrectAnswer' => $incorrectAnswer,
            'score' => $score
        ]);
    $questionTrackingTemplate = "";
    unset($_SESSION['answer'], $_SESSION['questions'], $_SESSION['firstInit'], $_SESSION['currentQuestion']);
}

echo $controller->jsonResponse([
    'showBtnPrev' => isset($showBtnPrev) ? $showBtnPrev : null,
    'endOfTest' => isset($_SESSION['endOfTest']) ? $_SESSION['endOfTest'] : null,
    'prevQuestionId' => isset($prevQuestionId) ? $prevQuestionId : null,
    'nextQuestionId' => isset($nextQuestionId) ? $nextQuestionId : null,
    'questionContent' => isset($questionTemplate) ? $questionTemplate : $endOfTestTemplate,
    'questionTrackingContent' => $questionTrackingTemplate,
    'showBtnBackToHome' => isset($showBtnBackToHome) ? $showBtnBackToHome : null,
    'questions' => count($_SESSION['answer'])
]);