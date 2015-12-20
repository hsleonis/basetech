/* angular/router/ -> router.js file, /* Router file */
app.config(function($routeProvider,$locationProvider) {
    $routeProvider
    .when('/', {
        templateUrl : 'templates/blank.html',
        controller  : 'mainController'
    })
    .when('/:page/:subpage', {
        templateUrl : 'templates/listview.html',
        controller  : 'listController'
    })
    .when('/projects/:subpage/:detail', {
        templateUrl : 'templates/project.html',
        controller  : 'projectController'
    })
    .when('/:page/:subpage/:detail', {
        templateUrl : 'templates/detail.html',
        controller  : 'listController'
    });
    $locationProvider.html5Mode(true);
});