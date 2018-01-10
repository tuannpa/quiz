<?php
if (AuthController::hasSignedIn()):
    require_once CONTROLLER_DIR . 'QuizController.php';

    if (!isset($_SESSION['endOfTest'])):
        $quizController = new QuizController($baseInstance->queryHelper);
        $questions = $quizController->generateRandomQuestions();
        $firstQuestion = $quizController->getFirstQuestion();
        $firstQuestion->id = $quizController->toInteger($firstQuestion->id);
        ?>
        <div class="preview" data-ng-controller="quizController">

            <h4 class="text-center quiz-heading">
                Phần Thi Trắc Nghiệm
            </h4>

            <div class="questionsBox"
                 data-ng-init="currentQuestionId = <?= $firstQuestion->id ?>; numOfQuestions = <?= $quizController->getTotalQuestions() ?>; selectedAnswer = <?= QuizController::getQuestionChoice() ?>"
                 cg-busy="{promise:loadQuestionPromise,message:'Đang tải..',backdrop:true,minDuration:1000,wrapperClass:'question-loading'}">

                <div class="ajaxReplace">
                    <div class="questions">Câu <?= isset($_SESSION['position']) ? $_SESSION['position'] : 1 ?>
                        . <?= $firstQuestion->content ?>
                    </div>
                    <ul class="answerList">
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="1">
                                <strong>A. </strong><?= $firstQuestion->first_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="2">
                                <strong>B. </strong><?= $firstQuestion->second_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="3">
                                <strong>C. </strong><?= $firstQuestion->third_choice ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio"
                                       name="answerGroup"
                                       class="pointer-on-hover"
                                       value="4">
                                <strong>D. </strong><?= $firstQuestion->fourth_choice ?>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="questionsRow">
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnBackToHome"
                            data-ng-click="backToHome()">Quay về trang chủ
                    </button>
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnPrev"
                            data-ng-click="loadQuestion('prev')">Quay lại
                    </button>
                    <button
                            class="button pointer-on-hover"
                            data-ng-if="showBtnNext"
                            data-ng-click="loadQuestion('next')">Tiếp tục
                    </button>

                    <span class="questionTracking">
                        <?= isset($_SESSION['position']) ? $_SESSION['position'] : 1 ?>
                        trong <?= $quizController->getTotalQuestions(); ?>
                    </span>
                </div>

            </div>

            <div class="row timing-section"
                 data-ng-if="showTiming">
                <h5 class="timing-content">Thời gian làm bài: {{timing | hhMmSs}}</h5>
            </div>

        </div>
<?php
    else:
        header('Location:?page=home');
    endif;
else:
    header('Location:?page=login');
endif;
?>

