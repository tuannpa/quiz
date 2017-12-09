var myApp = angular.module('myApp',[
    'toaster',
    'ngAnimate',
    'cgBusy',
    'angular-md5',
    'ngSanitize',
    'LocalStorageModule',
    'ngDialog'
]);
myApp.config([
    'localStorageServiceProvider', function (localStorageServiceProvider) {
    localStorageServiceProvider
        .setPrefix('myApp')
        .setStorageType('sessionStorage')
}]);
myApp.factory('isNullOrUndefined', function() {
   return function(val) {
       return angular.isUndefined(val) || val === null;
   }
});

myApp.controller('homeController', [
    '$scope',
    '$http',
    'md5',
    'toaster',
    '$timeout', function(
        $scope,
        $http,
        md5,
        toaster,
        $timeout) {
    $scope.password = '';
    $scope.passwordAgain = '';
    $scope.changePassword = function(form){
        $scope.updatePasswordPromise = $http({
            method: 'POST',
            url: 'ajax/home/changePassword.php',
            data: {
                password: md5.createHash($scope.password || ''),
                passwordAgain: md5.createHash($scope.passwordAgain || '')
            }
        })
            .then(function(response){
                var data = response.data;
                if(data.success) {
                    $timeout(function(){
                        toaster.pop({
                            type: 'success',
                            title: 'Thành công!',
                            body: 'Cập nhật thành công!',
                            timeout: 2500
                        });
                        $scope.password = '';
                        $scope.passwordAgain = '';
                        form.$setPristine();
                    },1200);
                }
                else {
                    $timeout(function(){
                        toaster.pop({
                            type: 'error',
                            title: 'Thất Bại!',
                            body: 'Có lỗi xảy ra!',
                            timeout: 2500
                        });
                        $scope.password = '';
                        $scope.passwordAgain = '';
                        form.$setPristine();
                    },1200);
                }
            });
    };
}])
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