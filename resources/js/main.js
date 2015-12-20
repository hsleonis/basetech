$(document).ready(function(){
    /* WOW JS */
    new WOW().init();
    
    // Menu Open Button
    $(document).on("click",".toggol-menu",function(){
        openMenu();
    });
    
    // Menu Close Button
    $(document).on("click",".menu-cross",function(){
        closeMenu();
    });
    
    // Menu Item
    $(document).on("click",".menu-list a",function(){
        closeMenu();
    });
    
    // Project Image
    $(document).on("click", ".img-ul-list img", function(){
        var src=$(this).attr('src');
        $('.product-img-v-board img').attr('src',src);
    });
    
});

var openMenu = function(){
    $.fn.fullpage.setAllowScrolling(false);
    
    $(".main-menu-wpr").toggleClass("menu-view");
    $('.nav-icon').css('opacity', '0');
}

var closeMenu = function(){
    if(!$('#main-view').hasClass('sub-paged'))
        $.fn.fullpage.setAllowScrolling(true);
    $(".main-menu-wpr").removeClass("menu-view");
    $(".nav-icon").toggleClass("barg-o-one");
    $('.nav-icon').css('opacity', '1');
}

angular.element('#contact').ready(function(){
    
});