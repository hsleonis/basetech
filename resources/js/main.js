$(document).ready(function(){
    /* WOW JS */
    new WOW().init();
    
    /* FullPage JS */
    /*$('#fullpage').fullpage({
        sectionSelector: '.section',
        slideSelector: '.slide'
    });*/
    
    // Menu open button
    $(document).on("click",".toggol-menu",function(){
        openMenu();
    });
    
    // Menu close button
    $(document).on("click",".menu-cross",function(){
        closeMenu();
    });
    
});

var openMenu = function(){
    $(".main-menu-wpr").toggleClass("menu-view");
        $('.nav-icon').css('opacity', '0');
        
    new WOW({
        boxClass:     'wow',     
        animateClass: 'animated',
        offset:       0,    
        mobile:       true,      
        live:         true 
    }).init();
}

var closeMenu = function(){
    $(".nav-icon").toggleClass("barg-o-one");
    $('.nav-icon').css('opacity', '1');
}

angular.element('#home').ready(function(){
    $('#fullpage').fullpage({
        sectionSelector: '.section',
        slideSelector: '.slide'
    });
});