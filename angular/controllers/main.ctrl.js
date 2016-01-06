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
        var fv = $routeParams.fvpage;
        
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
                    if(typeof fv!=='undefined' && typeof $scope.detPage.child_pages[fv]!=='undefined') {
                        $scope.fvTitle = $scope.detPage.child_pages[fv].page_data.page_title;
                        $scope.fvPage = $scope.detPage.child_pages[fv];
                        changeTitle($scope.fvTitle);
                    }
                    else {
                        $scope.fvTitle = '404 PAGE NOT FOUND';
                    }
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

// Search Controller
app.controller('searchController', function($scope, $http, JsonService, $routeParams, $window) {
    openView();
    changeTitle('SEARCH');
    $scope.company = 'SEARCH';
    $scope.subTitle = $routeParams.term;
    
    $http.post("http://dcastalia.com/projects/web/base/cms/site/search_data", {'term':$routeParams.term})
    .success(function (response) {
        $scope.results = response;
    });
    
/*    $scope.results = [
        {
            'page_title': 'First page',
            'page_url': 'http://dcastalia.com/projects/web/base/parent/sub/first-page',
            'page_thumb': 'http://dcastalia.com/projects/web/base/resources/img/1.jpg',
            'page_desc': 'This is the demo description',
            'page_result': '23'
        },
        {
            'page_title': 'Second page',
            'page_url': 'http://dcastalia.com/projects/web/base/parent/sub/first-page',
            'page_thumb': 'http://dcastalia.com/projects/web/base/resources/img/2.jpg',
            'page_desc': 'This is the demo description',
            'page_result': '5'
        }
    ];*/
});

// Links Controller
app.controller('linkController', function($scope, JsonService, $routeParams) {
    openView();
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'alliances';
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
        var currentPage = 'achievement';
        var sub = 'projects';
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
    
    $('.searchbox').keyup(function(e) {
         if (e.keyCode == 13) {
            $scope.$apply(function (){
                $location.url('/search/'+$scope.searchText);
                if(!$('#main-view').hasClass('sub-paged'))
                    $.fn.fullpage.setAllowScrolling(true);
                    $(".main-menu-wpr").removeClass("menu-view");
                    $(".nav-icon").toggleClass("barg-o-one");
                    $('.nav-icon').css('opacity', '1');
                });
        }
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