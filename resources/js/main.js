$(document).ready(function(){
    /* WOW JS */
    new WOW().init();
    
    /* FullPage JS */
    /*$('#fullpage').fullpage({
        sectionSelector: '.section',
        slideSelector: '.slide'
    });*/
    
    // Open Menu
    $(document).on("click",".toggol-menu",function(){
        $(".main-menu-wpr").toggleClass("menu-view");
        $('.nav-icon').css('opacity', '0');
        
        new WOW({
            boxClass:     'wow',     
            animateClass: 'animated',
            offset:       0,    
            mobile:       true,      
            live:         true 
        }).init();
    });
    
    // Close Menu
    $(document).on("click",".menu-cross",function(){
        $(".nav-icon").toggleClass("barg-o-one");
        $('.nav-icon').css('opacity', '1');
    });

});

angular.element('#home').ready(function(){
    $('#fullpage').fullpage({
        sectionSelector: '.section',
        slideSelector: '.slide'
    });
});