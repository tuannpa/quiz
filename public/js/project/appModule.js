var myApp = angular.module('myApp',[
    'toaster',
    'ngAnimate',
    'cgBusy',
    'angular-md5',
    'ngSanitize',
    'LocalStorageModule',
    'ngDialog',
    'ui.bootstrap.datetimepicker'
]).config([
    'localStorageServiceProvider', function (localStorageServiceProvider) {
        localStorageServiceProvider
            .setPrefix('myApp')
            .setStorageType('sessionStorage')
    }]);
