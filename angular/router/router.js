/* angular/router/ -> router.js file, /* Router file */
app.config(function($routeProvider,$locationProvider) {
    $routeProvider
    .when('/', {
        templateUrl : 'templates/blank.html',
        controller  : 'mainController'
    })
    .when('/:page', {
        templateUrl : 'templates/blank.html',
        controller  : 'mainController'
    })
    .when('/alliences/:subpage', {
        templateUrl : 'templates/links.html',
        controller  : 'linkController'
    })
    .when('/:page/:subpage', {
        templateUrl : 'templates/listview.html',
        controller  : 'listController'
    })
    .when('/media/events-gallery/:detail', {
        templateUrl : 'templates/gallery.html',
        controller  : 'galleryController'
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