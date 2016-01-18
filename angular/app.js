/*
 APP JS v1.0.1
 BASE TECHNOLOGIES. v3.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

// App
var modules = [
                'ngRoute',
                'ngStorage',
                'ngSanitize',
                'slick',
                'jsonService',
            ];

var app = angular.module('basetech', modules).run(function ($templateCache, $http, $route, $rootScope, $location) {
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
    
    var original = $location.path;
    $location.path = function (path, reload) {
        if (reload === false) {
            var lastRoute = $route.current;
            var un = $rootScope.$on('$locationChangeSuccess', function () {
                $route.current = lastRoute;
                un();
            });
        }
        return original.apply($location, [path]);
    };
});

app.config(function($sceProvider) {
  $sceProvider.enabled(false);
});