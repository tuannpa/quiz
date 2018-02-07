myApp.controller('homeController', homeController);

homeController.$inject = [
    '$scope',
    '$http',
    'md5',
    'toaster',
    '$timeout',
    '$window'
];

function homeController($scope, $http, md5, toaster, $timeout) {
    $scope.changePassword = function (form) {
        if (form.$valid) {
            $scope.updatePasswordPromise = $http({
                method: 'POST',
                url: 'ajax/home/changePassword.php',
                data: {
                    password: md5.createHash($scope.password)
                }
            }).then(function (response) {
                var data = response.data;
                $timeout(function () {
                    toaster.pop({
                        type: angular.lowercase(data.status),
                        title: data.status,
                        body: data.message,
                        timeout: 2500
                    });
                    $scope.password = '';
                    $scope.passwordAgain = '';
                    form.$setPristine();
                }, 1200);
            }).catch(function (error) {
                $timeout(function () {
                    var data = response.data;
                    toaster.pop({
                        type: 'error',
                        title: data.status,
                        body: data.message,
                        timeout: 2500
                    });
                    $scope.password = '';
                    $scope.passwordAgain = '';
                    form.$setPristine();
                }, 1200);
            });
        } else {
            angular.element('[data-toggle="popover"]').popover();
        }
    };
}