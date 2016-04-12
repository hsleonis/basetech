/*
 CONTROLLERS v1.0.1
 BASE TECHNOLOGIES. v4.1
 (c) 2015 DCASTALIA, http://dcastalia.com
 License: GPLv-3
 Author: MD. Hasan Shahriar
 Author email: hsleonis2@gmail.com

*/

app.controller('appController', function($http, $scope, $location, $window) {
    $scope.homeURL = location.origin+'/';
    $scope.career = {};
    $scope.career.sub = 'career';
    
    $scope.homepage = function(){
        closeView();
        if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
            $.fn.fullpage.setAllowScrolling(false);
            $.fn.fullpage.setKeyboardScrolling(false);
        }
        setTimeout(function(){
            $scope.$apply(function (){
                $location.url('/');
                if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
                    $.fn.fullpage.setAllowScrolling(true);
                    $.fn.fullpage.setKeyboardScrolling(true);
                }
            });
        },900);
    };
    
    $scope.top = function(){
        closeView();
        if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
            $.fn.fullpage.setAllowScrolling(false);
            $.fn.fullpage.setKeyboardScrolling(false);
        }
        setTimeout(function(){
            $scope.$apply(function (){
                $location.url('/');
                if(typeof $.fn.fullpage.setAllowScrolling !== 'undefined'){
                    $.fn.fullpage.moveTo(1);
                    $.fn.fullpage.setAllowScrolling(true);
                    $.fn.fullpage.setKeyboardScrolling(true);
                }
            });
        },900);
    };
    
    $scope.sendmail = function(a,b,c){
        alert("Please click Ok to send mail.");
        $http.post(location.origin+"/server/mail.php", {sub:'contact',name:a,email:b,message:c})
        .success(function (response) {
            //$scope.name = $scope.email = $scope.message = '';
            var s = response.replace(/(<\/div>|<div>|<\/p>|<p>|<\/ul>)/g, "").replace(/(<br\/>|<\/li>|<ul>)/g, "\n").replace(/<li>/g, "• ");
            if(response=='Mail successfully sent') {
                $('.mail-response').html(s);
                $('#enquiry-form').hide(100);
            }
            alert(s);
        });
    };
    
    $scope.sendapply = function(a){
        alert("Please click Ok to send mail.");
        $http.post(location.origin+"/server/mail.php", a)
        .success(function (response) {
            var s = response.replace(/(<\/div>|<div>|<\/p>|<p>|<\/ul>)/g, "").replace(/(<br\/>|<\/li>|<ul>)/g, "\n").replace(/<li>/g, "• ");
            if(response=='Mail successfully sent') {
		      s = "Thank you for showing your interest to be a BASE Citizen. We will get back to you, if you're shortlisted for a position.";
                $('.mail-response').text(s);
                $('#apply-form').hide(100);
            }
            alert(s);
        });
    };
});

function openView() {
    if(typeof $.fn.fullpage.setAllowScrolling!=='undefined'){
        $.fn.fullpage.setAllowScrolling(false);
        $.fn.fullpage.setKeyboardScrolling(false);
    }

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
    if(typeof $.fn.fullpage.setAllowScrolling!=='undefined'){
        $.fn.fullpage.setAllowScrolling(true);
        $.fn.fullpage.setKeyboardScrolling(true);
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

function orderObjectBy(items, field, reverse) {
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
                    
                    // var menuList = orderObjectBy($scope.page.menu,'sort_order',false);
                    var menuList = $scope.page.menu;
                    var index = menuList.map(function(x) {
                        return x.page_slug; 
                    }).indexOf($scope.detPage.page_data.page_slug);
                    
                    var prv = (index>0)?(index-1):(menuList.length-1);
                    var nxt = (index<menuList.length-1)?(index+1):0;
                    $scope.prvProject = menuList[prv].page_slug;
                    $scope.nxtProject = menuList[nxt].page_slug;
                    if(typeof fv!=='undefined' && typeof $scope.detPage.child_pages[fv]!=='undefined') {
                        $scope.fvTitle = $scope.detPage.child_pages[fv].page_data.page_title;
                        $scope.fvPage = $scope.detPage.child_pages[fv];
                        changeTitle($scope.fvTitle);
                        
                        // var menuList = orderObjectBy($scope.detPage.menu,'sort_order',false);
                        var menuList = $scope.detPage.menu;
                        var index = menuList.map(function(x) {
                            return x.page_slug; 
                        }).indexOf($scope.fvPage.page_data.page_slug);
                        
                        var prv = (index>0)?(index-1):(menuList.length-1);
                        var nxt = (index<menuList.length-1)?(index+1):0;
                        $scope.prvProject = menuList[prv].page_slug;
                        $scope.nxtProject = menuList[nxt].page_slug;
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
    
    $http.post(location.origin+"/cms/site/search_data", {'term':$routeParams.term})
    .success(function (response) {
        $scope.results = response;
    });
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
                    
                    // var menuList = orderObjectBy($scope.page.menu,'sort_order',false);
                    var menuList = $scope.page.menu;
                    var index = menuList.map(function(x) {
                        return x.page_slug; 
                    }).indexOf($scope.detPage.page_data.page_slug);
                    
                    var prv = (index>0)?(index-1):(menuList.length-1);
                    var nxt = (index<menuList.length-1)?(index+1):0;
                    $scope.prvProject = menuList[prv].page_slug;
                    $scope.nxtProject = menuList[nxt].page_slug;
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
            $('.nav-pills a:first').tab('show');
        },100);
    });
    
    $(document).on('click', '.nav-pills a' , function(event){
        if(typeof $.fn.fullpage.setAllowScrolling!=='undefined')
        $.fn.fullpage.setAllowScrolling(false);
    });
    
    $(document).on('click', '.mobile-search i, .mobile-search-sm i', function (){
        $scope.$apply(function (){
            $location.url('/search/'+$scope.searchText);
            if(!$('#main-view').hasClass('sub-paged'))
                $.fn.fullpage.setAllowScrolling(true);
                $(".main-menu-wpr").removeClass("menu-view");
                $(".nav-icon").toggleClass("barg-o-one");
                $('.nav-icon').css('opacity', '1');
        });
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
    $scope.limit = 0;
    
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
                    
                    // var menuList = orderObjectBy($scope.page.menu,'sort_order',false);
                    var menuList = $scope.page.menu;
                    var index = menuList.map(function(x) {
                        return x.page_slug; 
                    }).indexOf($scope.detPage.page_data.page_slug);
                    
                    var prv = (index>0)?(index-1):(menuList.length-1);
                    var nxt = (index<menuList.length-1)?(index+1):0;
                    $scope.prvProject = menuList[prv].page_slug;
                    $scope.nxtProject = menuList[nxt].page_slug;
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
    
    $scope.nxtPage = function(a,b){
        $scope.limit+=($scope.limit+a<b)?a:0;
    };
    
    $scope.prvPage = function(a){
        $scope.limit-=($scope.limit>=a)?a:0;
    };
});

// News Room Controller
app.controller('newsroomController', function($scope, JsonService, $routeParams, $filter) {
    openView();
    
    JsonService.get(function(pages){
        $scope.pages = pages;
        var currentPage = 'media';
        var sub = 'news-room';
        var detail = $routeParams.detail;
        var fv = $routeParams.fvpage;
        
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
                    
                    // var menuList = orderObjectBy($scope.page.menu,'sort_order',false);
                    var menuList = $scope.page.menu;
                    var index = menuList.map(function(x) {
                        return x.page_slug; 
                    }).indexOf($scope.detPage.page_data.page_slug);
                    
                    var prv = (index>0)?(index-1):(menuList.length-1);
                    var nxt = (index<menuList.length-1)?(index+1):0;
                    $scope.prvProject = menuList[prv].page_slug;
                    $scope.nxtProject = menuList[nxt].page_slug;
                    
                    if(typeof fv!=='undefined' && typeof $scope.detPage.child_pages[fv]!=='undefined') {
                        $scope.fvTitle = $scope.detPage.child_pages[fv].page_data.page_title;
                        $scope.fvPage = $scope.detPage.child_pages[fv];
                        changeTitle($scope.fvTitle);
                        
                        // var menuList = orderObjectBy($scope.detPage.menu,'sort_order',false);
                        var menuList = $scope.detPage.menu;
                        var index = menuList.map(function(x) {
                            return x.page_slug; 
                        }).indexOf($scope.fvPage.page_data.page_slug);

                        var prv = (index>0)?(index-1):(menuList.length-1);
                        var nxt = (index<menuList.length-1)?(index+1):0;
                        $scope.prvProject = menuList[prv].page_slug;
                        $scope.nxtProject = menuList[nxt].page_slug;
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

// Home News Controller
app.controller('newstickerController', function($scope, $http, $routeParams, $filter) {
    $http.get(location.origin+'/projects/web/base/cms/administrator/json/news.json',{}).success(function(data){
        $scope.newsticker = data["home-news"];
    });
});

// Testimonial Controller
app.controller('testimonialController', function($scope, JsonService, $routeParams, $location) {
    JsonService.get(function(pages){
        $scope.data = pages['about'].child_pages['about-base-technologies'].child_pages['testimonial'].child_pages;
    });
});

// Leader Controller
app.controller('leaderController', function($scope, JsonService, $routeParams, $location) {
    JsonService.get(function(pages){
        $scope.data = pages['about'].child_pages['about-base-technologies'].child_pages['messege-from-leaders'].child_pages;
    });
});