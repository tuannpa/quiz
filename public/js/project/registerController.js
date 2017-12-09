myApp.controller('registerController', registerController);

registerController.$inject = [
    '$scope',
    'ngDialog'
];

function registerController($scope, 
                            ngDialog) {
    $scope.openRegisterForm = function () {
        ngDialog.open({
            template: 'registerForm',
            className: 'ngdialog-theme-default',
            width: '600px'
        });
    }
}