<?php
if (!is_bool(AuthController::verifyToken())):
    require_once CONTROLLER_DIR . 'QuizController.php';

    if (!isset($_SESSION['endOfTest'])):
        $quizController = new QuizController($baseInstance->queryHelper);
        $questions = $quizController->generateRandomQuestions();
        $currentQuestion = $quizController->getCurrentQuestion();
        $currentQuestion->id = (int)$currentQuestion->id;
        ?>
        <div class="preview" data-ng-controller="quizController">

            <h4 class="text-center quiz-heading">
                Quiz Section
            </h4>

            <div class="questionsBox"
                 data-ng-init="currentQuestionId = <?= $currentQuestion->id ?>; numOfQuestions = <?= $quizController->getTotalQuestions() ?>; selectedAnswer = <?= QuizController::getQuestionChoice() ?>"
                 cg-busy="{promise:loadQuestionPromise,message:'Loading..',backdrop:true,minDuration:1000,wrapperClass:'question-loading'}">

                <div class="ajaxReplace">
                    <div class="questions">Question <?= isset($_SESSION['position']) ? $_SESSION['position'] : 1 ?>
                        . <?= $currentQuestion->content ?>
                    </div>
                    <ul class="answerList">
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="1">
                                <strong>A. </strong><?= $currentQuestion->first_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="2">
                                <strong>B. </strong><?= $currentQuestion->second_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="3">
                                <strong>C. </strong><?= $currentQuestion->third_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="4">
                                <strong>D. </strong><?= $currentQuestion->fourth_choice ?>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="questionsRow">
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnBackToHome"
                            data-ng-click="backToHome()">Back to Homepage
                    </button>
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnPrev"
                            data-ng-click="loadQuestion('prev')">Prev
                    </button>
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnNext"
                            data-ng-click="loadQuestion('next')">Next
                    </button>

                    <span class="questionTracking">
                        <?= isset($_SESSION['position']) ? $_SESSION['position'] : 1 ?>
                        / <?= $quizController->getTotalQuestions(); ?>
                    </span>
                </div>

            </div>

            <div class="row timing-section"
                 data-ng-if="showTiming">
                <h5 class="timing-content">Timing Clock: {{timing | hhMmSs}}</h5>
            </div>

        </div>
<?php
    else:
        header('Location:index.php');
    endif;
else:
    header('Location:?page=login');
endif;
?>

