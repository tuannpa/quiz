myApp.controller('registerController', registerController);

registerController.$inject = [
    '$scope',
    'ngDialog',
    'md5',
    '$http'
];

function registerController($scope, 
                            ngDialog,
                            md5,
                            $http) {
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