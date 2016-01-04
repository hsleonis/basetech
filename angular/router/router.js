/*
 ROUTER v1.0.1
 BASE TECHNOLOGIES. v3.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

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
    .when('/media/news-room/:detail', {
        templateUrl : 'templates/newsroom.html',
        controller  : 'newsroomController'
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