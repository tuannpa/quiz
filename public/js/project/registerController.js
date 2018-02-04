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
            if ($scope.dateOfBirth) {

            }
            $scope.registerPromise = $http({
                method: 'POST',
                url: 'ajax/register/doRegister.php',
                data: JSON.stringify({
                    name: $scope.name,
                    username: $scope.username,
                    password: md5.createHash($scope.password),
                    dateOfBirth: $scope.dateOfBirth || '',
                    gender: $scope.gender || '',
                    csrfToken: $scope.csrfToken
                })
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