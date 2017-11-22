myApp.filter('hhMmSs', function () {
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

myApp.controller('quizController', [
    '$scope',
    '$http',
    '$timeout',
    '$interval',
    '$window',
    'isNullOrUndefined', function ($scope,
                                   $http,
                                   $timeout,
                                   $interval,
                                   $window,
                                   isNullOrUndefined) {
        $scope.timing = 0;
        var promise = $interval(function () {
            $scope.timing++;
        }, 1000);

        $scope.questionsCount = 0;
        $scope.showBtnBackToHome = false;
        console.log($scope.questionsCount);
        if ($scope.questionsCount > 0) {
            console.log('haha');
            $scope.showBtnPrev = true;
        } else {
            console.log('hihi');
            $scope.showBtnPrev = false;
        }
        $scope.showBtnNext = true;
        $scope.showTiming = true;
        $scope.nextQuestion = 'next';
        $scope.prevQuestion = 'prev';
        //$interval.cancel(promise);

        $scope.backToHome = function () {
            $window.location.href = '?page=home';
        };

        $scope.loadQuestion = function (task) {
            if (angular.element('input[type=radio]').is(':checked')) {
                $scope.loadQuestionPromise = $http({
                    method: 'POST',
                    url: 'ajax/loadQuestion.php?task=' + task,
                    data: {
                        currentQuestionId: $scope.currentQuestionId,
                        userChoice: angular.element('input[name=answerGroup]:checked').val(),
                        totalQuestions: $scope.numOfQuestions,
                        totalTime: $scope.timing
                    }
                }).then(function (response) {
                    console.log($scope.questionsCount);
                    var data = response.data;
                    $scope.currentQuestionId = (!isNullOrUndefined(data.nextQuestionId)) ? data.nextQuestionId : data.prevQuestionId;
                    $timeout(function () {
                        if (!isNullOrUndefined(data.showBtnBackToHome)) {
                            $scope.showBtnBackToHome = data.showBtnBackToHome;
                            $scope.showBtnPrev = data.showBtnPrev;
                            $scope.showBtnNext = false;
                            $scope.showTiming = false;
                        } else {
                            $scope.showBtnPrev = data.showBtnPrev;
                        }
                        angular.element('.ajaxReplace').replaceWith(data.questionContent);
                        angular.element('.questionTracking').replaceWith(data.questionTrackingContent);
                    }, 1200);
                });
            } else {
                alert('Bạn chưa chọn câu trả lời!');
            }
        }
    }]);

