myApp.controller('quizController', quizController)
    .filter('hhMmSs', function () {
    return function (time) {
        var sec = parseInt(time, 10);
        var hours = Math.floor(sec / 3600);
        var minutes = Math.floor((sec - (hours * 3600)) / 60);
        var seconds = sec - (hours * 3600) - (minutes * 60);

        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        var currentTime = hours + ':' + minutes + ':' + seconds;
        return currentTime;
    }
});

quizController.$inject = [
    '$scope',
    '$http',
    '$timeout',
    '$interval',
    '$window',
    'isNullOrUndefined',
    'localStorageService'
];

function quizController($scope,
                        $http,
                        $timeout,
                        $interval,
                        $window,
                        isNullOrUndefined,
                        localStorageService) {
    var increaseTiming = function () {
        $interval(function () {
            ++$scope.timing;
            localStorageService.set('timingAfterReload', $scope.timing);
        }, 1000);
    };

    var questions = localStorageService.get('numOfQuestions');
    var timingAfterReload = localStorageService.get('timingAfterReload');

    $scope.$watch('selectedAnswer', function () {
        if ($scope.selectedAnswer) {
            angular.element('.answerList').find(':radio[name=answerGroup][value="' + $scope.selectedAnswer + '"]').prop('checked', true);
        }
    });

    if (performance.navigation.type == 1) {
        $scope.timing = (questions >= 1) ? timingAfterReload : 0;
        increaseTiming();
    } else {
        $scope.timing = (timingAfterReload > 0 && questions >= 1) ? timingAfterReload : 0;
        increaseTiming();
    }

    $scope.showBtnPrev = (questions >= 1);
    $scope.showBtnBackToHome = false;
    $scope.showBtnNext = true;
    $scope.showTiming = true;
    //$interval.cancel(promise);

    $scope.backToHome = function () {
        $window.location.href = '?page=home';
    };

    $scope.loadQuestion = function (task) {
        if (angular.element('input[type=radio]').is(':checked')) {
            $scope.loadQuestionPromise = $http({
                method: 'POST',
                url: 'ajax/quiz/loadQuestion.php?task=' + task,
                data: {
                    currentQuestionId: $scope.currentQuestionId,
                    userChoice: angular.element('input[name=answerGroup]:checked').val(),
                    totalQuestions: $scope.numOfQuestions,
                    totalTime: $scope.timing
                }
            }).then(function (response) {
                var data = response.data;
                $scope.currentQuestionId = (!isNullOrUndefined(data.nextQuestionId)) ? data.nextQuestionId : data.prevQuestionId;
                $timeout(function () {
                    if (!isNullOrUndefined(data.showBtnBackToHome)) {
                        $scope.showBtnBackToHome = data.showBtnBackToHome;
                        $scope.showBtnPrev = data.showBtnPrev;
                        $scope.showBtnNext = false;
                        $scope.showTiming = false;
                        localStorageService.remove('numOfQuestions', 'timingAfterReload');
                    } else {
                        $scope.showBtnPrev = data.showBtnPrev;
                        localStorageService.set('numOfQuestions', data.questions);
                    }
                    angular.element('.ajaxReplace').replaceWith(data.questionContent);
                    angular.element('.answerList').find(':radio[name=answerGroup][value="' + data.selectedChoice + '"]').prop('checked', true);
                    angular.element('.questionTracking').replaceWith(data.questionTrackingContent);
                }, 1200);
            });
        } else {
            alert('Bạn chưa chọn câu trả lời!');
        }
    }
}

