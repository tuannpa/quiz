myApp.controller('homeController', homeController)
    .directive('compareTo', [ function(){
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function (scope, element, attributes, ngModel) {

                ngModel.$validators.compareTo = function (modelValue) {
                    return modelValue === scope.otherModelValue;
                };

                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
}]);

homeController.$inject = [
    '$scope',
    '$http',
    'md5',
    'toaster',
    '$timeout',
    '$window'
];

function homeController($scope, $http, md5, toaster, $timeout) {
    $scope.changePassword = function(form){
        if (form.$valid) {
            $scope.updatePasswordPromise = $http({
                method: 'POST',
                url: 'ajax/home/changePassword.php',
                data: {
                    password: md5.createHash($scope.password)
                }
            }).then(function(response){
                var data = response.data;
                $timeout(function () {
                    toaster.pop({
                        type: data.status ? 'success' : 'error',
                        title: data.status ? 'Success!' : 'Error!',
                        body: data.status ? 'Password has been updated!' : 'Unknown error!',
                        timeout: 2500
                    });
                    $scope.password = '';
                    $scope.passwordAgain = '';
                    form.$setPristine();
                }, 1200);
            }).catch(function (error) {
                $timeout(function () {
                    toaster.pop({
                        type: 'error',
                        title: error.status + 'error',
                        body: error.statusText,
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