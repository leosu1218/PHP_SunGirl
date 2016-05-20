require(
    [
        // Dependencies libs
        'angular',
        'ngRoute',
        'ngBootstrap',
        'ngSanitize',
        'ngAnimate',
        'bootstrap',

        // Angular controllers/services
        'app',
        'Router'
    ],
    function (angular) {
        angular.bootstrap(document, ['app']);
    }
);