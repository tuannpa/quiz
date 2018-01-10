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
    '$timeout'
];

function homeController($scope, $http, md5, toaster, $timeout) {
    $scope.password = '';
    $scope.passwordAgain = '';
    $scope.changePassword = function(form){
        if (form.$valid) {
            $scope.updatePasswordPromise = $http({
                method: 'POST',
                url: 'ajax/home/changePassword.php',
                data: {
                    password: md5.createHash($scope.password || ''),
                    passwordAgain: md5.createHash($scope.passwordAgain || '')
                }
            }).then(function(response){
                var data = response.data;
                $timeout(function () {
                    toaster.pop({
                        type: data.status ? 'success' : 'error',
                        title: data.status ? 'Thành công!' : 'Thất Bại!',
                        body: data.status ? 'Cập nhật thành công!' : 'Có lỗi xảy ra!',
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