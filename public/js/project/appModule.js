var myApp = angular.module('myApp', [
    'toaster',
    'ngAnimate',
    'cgBusy',
    'angular-md5',
    'ngSanitize',
    'LocalStorageModule',
    'ngDialog',
    'ui.bootstrap.datetimepicker',
    'ui.dateTimeInput'
]).config([
    'localStorageServiceProvider', function (localStorageServiceProvider) {
        localStorageServiceProvider
            .setPrefix('myApp')
            .setStorageType('sessionStorage')
    }])
    .directive('compareTo', [function () {
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
