myApp.controller('registerController', registerController);

registerController.$inject = [
    '$scope',
    'ngDialog',
    'md5',
    '$http',
    'toaster',
    '$timeout'
];

function registerController($scope,
                            ngDialog,
                            md5,
                            $http,
                            toaster,
                            $timeout) {
    $scope.doRegister = function (form) {
        if (form.$valid) {
            angular.element('.user-register').find('.cg-busy-default-wrapper').addClass('regiter-loading');
            if ($scope.dateOfBirth) {
                var formattedDOB = moment($scope.dateOfBirth).local().format('YYYY-MM-DD');
            }
            $scope.registerPromise = $http({
                method: 'POST',
                url: 'ajax/register/doRegister.php',
                data: {
                    name: $scope.name,
                    username: $scope.username,
                    password: md5.createHash($scope.password),
                    dateOfBirth: formattedDOB || '',
                    gender: $scope.gender || '',
                    csrfToken: $scope.csrfToken
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
                    ngDialog.close();
                }, 1200);
            }).catch(function (error) {
                var data = error.data;
                $timeout(function () {
                    toaster.pop({
                        type: 'error',
                        title: data['statusCode'] ? data['statusCode'] : data.status,
                        body: data.message,
                        timeout: 2500
                    });
                }, 1200);
                form.$setPristine();
            })
        }
    };

    $scope.openRegisterForm = function () {
        ngDialog.open({
            template: 'registerForm',
            className: 'ngdialog-theme-default',
            width: '450px',
            controller: 'registerController'
        });
    }
}