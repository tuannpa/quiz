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
