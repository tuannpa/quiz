myApp.controller('registerController', registerController);

registerController.$inject = [
    '$scope',
    'ngDialog'
];

function registerController($scope, 
                            ngDialog) {
    $scope.test = 'asdasdsa';
    $scope.openRegisterForm = function () {
        ngDialog.open({
            template: 'registerForm',
            className: 'ngdialog-theme-default',
            width: '450px',
            controller: 'registerController'
        });
    }
}