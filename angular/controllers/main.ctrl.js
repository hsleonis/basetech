/*
 CONTROLLERS v1.0.1
 BASE TECHNOLOGIES. v3.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

app.controller('appController', function($scope, $location, $window) {
    $scope.homeURL = 'http://dcastalia.com/projects/web/base/';
    
    $scope.homepage = function(){
        closeView();
        setTimeout(function(){
            $scope.$apply(function (){
                $location.url('/');
            });
        },900);
    };
});

function openView() {
    if(typeof $.fn.fullpage.setAllowScrolling!=='undefined')
        $.fn.fullpage.setAllowScrolling(false);

    $('#main-view').addClass('sub-paged');
    $('.main-menu-content').css({'background-color':'#333E3A'});
    setTimeout(function(){
        $('#main-view').css({
            left: '0',
        });
        $('#fullpage').css({
            left: '-100%'
        });
    },200);
    
};

function closeView() {
    if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
        $.fn.fullpage.setAllowScrolling(true);
    }
    $('#main-view').removeClass('sub-paged');
    $('.main-menu-content').css({'background-color':''});
    setTimeout(function(){
        $('#main-view').css({
            left: '100%',
        });
        $('#fullpage').css({
            left: '0'
        });
    },200);
};

function changeTitle(title) {
    document.title = title.toUpperCase() + " | BASE Technologies";
};

// Main Controller
app.controller('mainController', function($scope) {
    closeView();
    
    $scope.message = 'Home page';
    changeTitle('Home');
});

// List Controller
app.controller('listController', function($scope, JsonService, $routeParams) {
    openView();
    
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
                changeTitle($scope.subTitle);
                if(typeof detail!=='undefined' && typeof $scope.page.child_pages[detail]!=='undefined') {
                    $scope.detTitle = $scope.page.child_pages[detail].page_data.page_title;
                    $scope.detPage = $scope.page.child_pages[detail];
                    changeTitle($scope.detTitle);
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

// Links Controller
app.controller('linkController', function($scope, JsonService, $routeParams) {
    openView();
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'alliences';
        var sub = $routeParams.subpage;
        var detail = $routeParams.detail;
        
        if(typeof pages[currentPage]!=='undefined'){
            $scope.cur = pages[currentPage];
            $scope.company = $scope.cur.page_data.page_title;
            
            if(typeof $scope.cur.child_pages[sub]!=='undefined'){
                $scope.subTitle = $scope.cur.child_pages[sub].page_data.page_title;
                $scope.page = $scope.cur.child_pages[sub];
                changeTitle($scope.subTitle);
                if(typeof detail!=='undefined' && typeof $scope.page.child_pages[detail]!=='undefined') {
                    $scope.detTitle = $scope.page.child_pages[detail].page_data.page_title;
                    $scope.detPage = $scope.page.child_pages[detail];
                    changeTitle($scope.detTitle);
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

// Project Controller
app.controller('projectController', function($scope, JsonService, $routeParams) {
    openView();
    
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
                    changeTitle($scope.detTitle);
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
    
    $(document).on('click', '.nav-pills a' , function(event){
        if(typeof $.fn.fullpage.setAllowScrolling!=='undefined')
        $.fn.fullpage.setAllowScrolling(false);
    });
});

// Gallery Controller
app.controller('galleryController', function($scope, JsonService, $routeParams) {
    openView();
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'media';
        var sub = 'events-gallery'; //$routeParams.subpage;
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
                    changeTitle($scope.detTitle);
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

// News Room Controller
app.controller('newsroomController', function($scope, JsonService, $routeParams) {
    openView();
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'media';
        var sub = 'news-room';
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
                    changeTitle($scope.detTitle);
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