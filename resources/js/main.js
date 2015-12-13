$(document).ready(function(){
    /*Section Slider*/
    $('#fullpage').fullpage({
        sectionSelector: '.section',
        slideSelector: '.slide'
    });

    /*Main Menu Toggol*/
    $(".toggol-menu").click(function(){
        $(".main-menu-wpr").toggleClass("menu-view");
    });

    /*Slick Slider*/
    $('.slick-slider-wrp').slick({
        dots: false,
        infinite: true,
        speed: 500,
        autoplay: true,
        autoplaySpeed: 3000,
        fade: true,
        cssEase: 'linear'
    });
});