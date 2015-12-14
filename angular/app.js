/*
 APP JS v1.0.1
 BASE TECHNOLOGIES. v3.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

// App
var app = angular.module('basetech', ['ngRoute', 'ngStorage', 'ngSanitize', 'slick']).run(function ($templateCache, $http) {
    $http.get('templates/menu.html', {
        cache: $templateCache
    });
    $http.get('templates/topbar.html', {
        cache: $templateCache
    });
    $http.get('templates/slider.html', {
        cache: $templateCache
    });
    $http.get('templates/solutions.html', {
        cache: $templateCache
    });
});