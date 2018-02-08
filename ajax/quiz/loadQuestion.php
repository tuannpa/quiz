<?php
session_start();
require_once '../ajaxConfig.php';
require_once CONTROLLER_DIR . 'QuizController.php';
require_once HELPER_DIR . 'TemplateHelper.php';

if (!is_bool($token = AuthController::verifyToken($_COOKIE['token']))) {
    $controller = new QuizController();
    $params = QuizController::getRequestPayload();
    $request = $controller->getUrlParams();
    $userInfo = AuthController::getUserInfo($token);

    if (!isset($_SESSION['answer'])) {
        $_SESSION['answer'] = [];
    }

    $_SESSION['answer'][$params->currentQuestionId] = $params->userChoice;

    foreach ($_SESSION['questions'] as $key => $questionId) {
        if ($request->task == 'prev') {
            if ($params->currentQuestionId == $questionId) {
                $showBtnPrev = (($key - 1) != 0) ? true : false;
                $prevQuestionId = $_SESSION['questions'][$key - 1];
                $selectedChoice = $controller->getSelectedChoice($prevQuestionId);
                $_SESSION['position'] = $key;
            }
        } else {
            if ($params->currentQuestionId == $questionId) {
                $showBtnPrev = ($key >= 0) ? true : false;
                $_SESSION['endOfTest'] = ($key == (count($_SESSION['questions']) - 1)) ? true : null;
                $totalTime = ($key == (count($_SESSION['questions']) - 1)) ? $params->totalTime : null;
                $nextQuestionId = (!isset($_SESSION['endOfTest'])) ? $_SESSION['questions'][$key + 1] : null;
                $selectedChoice = $controller->getSelectedChoice($nextQuestionId);
                $_SESSION['position'] = (!isset($_SESSION['endOfTest'])) ? $key + 2 : count($_SESSION['questions']);
                $showBtnBackToHome = (isset($_SESSION['endOfTest'])) ? true : null;
                if (isset($_SESSION['endOfTest'])) {
                    $showBtnPrev = false;
                }
            }
        }
    }

    if (isset($nextQuestionId)) {
        $_SESSION['currentQuestion'] = $nextQuestionId;
    } elseif (isset($prevQuestionId)) {
        $_SESSION['currentQuestion'] = $prevQuestionId;
    }

    if (!isset($_SESSION['endOfTest'])) {
        $id = isset($nextQuestionId) ? $nextQuestionId : $prevQuestionId;
        $question = $controller->loadQuestion($id);

        $questionTemplate = TemplateHelper::setFilePath('template/questionTemplate.html')
            ->renderTemplate([
                'questionId' => $_SESSION['position'],
                'questionContent' => $question->content,
                'firstChoice' => $question->first_choice,
                'secondChoice' => $question->second_choice,
                'thirdChoice' => $question->third_choice,
                'fourthChoice' => $question->fourth_choice
            ]);

        $questionTrackingTemplate = TemplateHelper::setFilePath('template/questionTrackingTemplate.html')
            ->renderTemplate([
                'position' => $_SESSION['position'],
                'totalQuestions' => $params->totalQuestions
            ]);
    } else {
        // TODO: Refactor, check if id is changeable to make this more dynamically
        $allQuestions = $controller->loadQuestionByCateId();
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

        $controller->saveQuizResult([
            $userInfo->id,
            $correctAnswer,
            $incorrectAnswer,
            $score,
            $totalTime
        ]);
        $endOfTestTemplate = TemplateHelper::setFilePath('template/endOfTestTemplate.html')
            ->renderTemplate([
                'correctAnswer' => $correctAnswer,
                'incorrectAnswer' => $incorrectAnswer,
                'totalTime' => $controller->formatTime($totalTime),
                'score' => $score
            ]);
        $questionTrackingTemplate = "";
        unset($_SESSION['answer'], $_SESSION['questions'], $_SESSION['firstInit'], $_SESSION['currentQuestion'], $_SESSION['position']);
    }

    $controller->jsonResponse([
        'showBtnPrev' => isset($showBtnPrev) ? $showBtnPrev : null,
        'endOfTest' => isset($_SESSION['endOfTest']) ? $_SESSION['endOfTest'] : null,
        'prevQuestionId' => isset($prevQuestionId) ? $prevQuestionId : null,
        'nextQuestionId' => isset($nextQuestionId) ? $nextQuestionId : null,
        'selectedChoice' => isset($selectedChoice) ? $selectedChoice : null,
        'questionContent' => isset($questionTemplate) ? $questionTemplate : $endOfTestTemplate,
        'questionTrackingContent' => $questionTrackingTemplate,
        'showBtnBackToHome' => isset($showBtnBackToHome) ? $showBtnBackToHome : null,
        'questions' => isset($_SESSION['answer']) ? count($_SESSION['answer']) : null
    ]);
} else {
    QuizController::response(Config::UNAUTHORIZED_CODE, 'Unauthorized');
}