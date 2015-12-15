/* angular/router/ -> router.js file, /* Router file */
app.config(function($routeProvider,$locationProvider) {
    $routeProvider
    .when('/', {
        controller  : 'mainController'
    })
    .when('/:page', {
        templateUrl : 'templates/listview.html',
        controller  : 'listController'
    });
    $locationProvider.html5Mode(true);
});