require(
    [
        // Dependencies libs
        'angular',
        'ngRoute',
        'ngBootstrap',
        'ngSanitize',
        'ngAnimate',
        'ngCarousel',
        'bootstrap',
        'smoothscroll',
        'sunGirl',
        'sunGirlPlugin',

        // Directives
        'directives/AppHeader/controller',
        'directives/AppFooter/controller',

        // Angular controllers/services
        'app',
        'Router'
    ],
    function (angular) {
        angular.bootstrap(document, ['app']);
    }
);