var myApp = angular.module('myApp',[
    'toaster',
    'ngAnimate',
    'cgBusy',
    'angular-md5',
    'ngSanitize',
    'LocalStorageModule',
    'ngDialog'
]).config([
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