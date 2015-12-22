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

$(window).load(function(){
    setTimeout(function(){
        $('.area:not(#contact-us .area),.area:not(#about-us .area)').each(function(){
            var t = document.createElement('div');
            $(t).addClass('translucent');
            $(this).append(t);
            $(t).css({'background-image': $(this).css('background-image')});
            console.log($(this).css('background-image'));
        });
        $('.translucent').css({
            'position': 'absolute',
            'background-size' : 'cover',
            'width': '100%',
            'height': '100%',
            'top': '0',
            'left': '0',
            'filter': 'blur(5px)',
            'z-index': '400',
            'opacity' : '0',
            'transition' : '.5s'
        });
        $(document).on('mouseover','.translucent',function(){
            $(this).animate({
                'opacity' : '1'
            },200);
        });
        $(document).on('mouseout','.translucent',function(){
            $(this).animate({
                'opacity' : '0'
            },200);
        });
    },500);
});