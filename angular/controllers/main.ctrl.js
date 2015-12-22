/* angular/controllers/ -> main.ctrl.js file */

app.controller('appController', function($scope) {
    $scope.homeURL = 'http://localhost/base/';
});

// Main Controller
app.controller('mainController', function($scope) {
    if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
        $.fn.fullpage.setAllowScrolling(true);
    }
    $('#main-view').removeClass('sub-paged');
    setTimeout(function(){
        $('#main-view').animate({
            left: '100px',
            opacity: 0,
        },500,function(){
            $('#main-view').css({'z-index':'-1'});
        });
    },200);
    
    $scope.message = 'Home page';
});

// List Controller
app.controller('listController', function($scope, JsonService, $routeParams) {
    if(typeof $.fn.fullpage.setAllowScrolling!=='undefined')
        $.fn.fullpage.setAllowScrolling(false);
    $('#main-view').addClass('sub-paged');
    setTimeout(function(){
        $('#main-view').animate({
            'z-index': '0'
        },50,function(){
            $('#main-view').animate({
                left: '0px',
                opacity: 1
            },500, function(){
                $(".nav-icon").toggleClass("barg-o-one");
                $('.nav-icon').css('opacity', '1');
            });
        });
    },200);
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = $routeParams.page;
        var sub = $routeParams.subpage;
        var detail = $routeParams.detail;
        
        if(typeof pages[currentPage]!=='undefined'){
            $scope.cur = pages[currentPage];
            $scope.company = $scope.cur.page_data.page_title;
            
            if(typeof $scope.cur.child_pages[sub]!=='undefined'){
                $scope.subTitle = $scope.cur.child_pages[sub].page_data.page_title;
                $scope.page = $scope.cur.child_pages[sub];
                if(typeof detail!=='undefined' && typeof $scope.page.child_pages[detail]!=='undefined') {
                    $scope.detTitle = $scope.page.child_pages[detail].page_data.page_title;
                    $scope.detPage = $scope.page.child_pages[detail];
                }
                else {
                    $scope.detTitle = '404 PAGE NOT FOUND';
                }
            }
            else{
                $scope.subTitle = '404 PAGE NOT FOUND';
            }
        }
        else{
            $scope.company = '404 PAGE NOT FOUND';
            $scope.subTitle = '';
        }
    });
});

app.controller('projectController', function($scope, JsonService, $routeParams) {
    $.fn.fullpage.setAllowScrolling(false);
    $('#main-view').addClass('sub-paged');
    setTimeout(function(){
        $('#main-view').animate({
            'z-index': '0'
        },50,function(){
            $('#main-view').animate({
                left: '0px',
                opacity: 1
            },500, function(){
                $(".nav-icon").toggleClass("barg-o-one");
                $('.nav-icon').css('opacity', '1');
            });
        });
    },200);
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'projects';
        var sub = $routeParams.subpage;
        var detail = $routeParams.detail;
        
        if(typeof pages[currentPage]!=='undefined'){
            $scope.cur = pages[currentPage];
            $scope.company = $scope.cur.page_data.page_title;
            
            if(typeof $scope.cur.child_pages[sub]!=='undefined'){
                $scope.subTitle = $scope.cur.child_pages[sub].page_data.page_title;
                $scope.page = $scope.cur.child_pages[sub];
                if(typeof detail!=='undefined' && typeof $scope.page.child_pages[detail]!=='undefined') {
                    $scope.detTitle = $scope.page.child_pages[detail].page_data.page_title;
                    $scope.detPage = $scope.page.child_pages[detail];
                    console.log($scope.detPage);
                }
                else {
                    $scope.detTitle = '404 PAGE NOT FOUND';
                }
            }
            else{
                $scope.subTitle = '404 PAGE NOT FOUND';
            }
        }
        else{
            $scope.company = '404 PAGE NOT FOUND';
            $scope.subTitle = '';
        }
    });
});


// Menu Controller
app.controller('menuController', function($scope, JsonService, $routeParams, $location) {
    JsonService.get(function(pages){
        $scope.pages = pages;
        setTimeout(function(){
            $('.nav-pills').tab();
            $('.nav-pills a:last').tab('show');
        },100);
    });
});