/*
 DIRECTIVES v1.0.1
 BASE TECHNOLOGIES. v3.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

app.directive('bindUnsafeHtml', ['$compile', function ($compile) {
    return function (scope, element, attrs) {
        scope.$watch(
            function (scope) {
                return scope.$eval(attrs.bindUnsafeHtml);
            },
            function (value) {
                element.html(value);
                $compile(element.contents())(scope);
            }
        );
    };
}]);

app.directive('ngScroll', function() {
    return {
        restrict: 'AM',
        link: function(scope, element, attrs){
            $(document).ready(function () {
                element.perfectScrollbar();
                setTimeout(function(){
                    element.perfectScrollbar('update');
                },50);
            });
        }
    }
});

app.filter('substring',function(){
    return function(str, ch) {
        return str.substring(0, ch);
    };
});

app.directive('lightgallery', function () {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            if (scope.$last) {
                element.parent().lightGallery({
                        thumbnail:true,
                        animateThumb: false,
                        showThumbByDefault: false
                });
            }
        }
    };
});

app.directive('ngImageLoadAnimation', function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.css({'opacity': 0});
            element.imagesLoaded(function(){
                $(element).animate({
                    'opacity': 1
                }, 1000,
                    function() {
                });
            });
        }
    }
});

app.filter('orderObjectBy', function() {
  return function(items, field, reverse) {
    var filtered = [];
    angular.forEach(items, function(item) {
      filtered.push(item);
    });
    filtered.sort(function (a, b) {
      return (a[field] > b[field] ? 1 : -1);
    });
    if(reverse) filtered.reverse();
    return filtered;
  };
});