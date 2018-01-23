(function (angular) {
    angular.isNullOrUndefined = function (value) {
        return angular.isUndefined(value) || value === null;
    };

    angular.isArrayAndHasData = function (value) {
        return angular.isArray(value) && value.length > 0;
    };
})(angular);