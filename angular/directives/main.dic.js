/* angular/directives/ -> main.dic.js file */

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