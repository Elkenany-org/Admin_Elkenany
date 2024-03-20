'use strict';

//--------------------------------Start Home page-------------------------//
////////////////Start Switch between search tabs////////////////
$('.two-way-search-box .title-right').click(function () {
    $(this).css('border-bottom-color', '#FFAA00');

    $('.title-left').css('border-bottom-color', '#3d9c12');
    $('.title-left').css('color', '#1a5302');
    $('.title-right').css('color', '#FFAA00');

    $('.body').css('display', 'none').fadeIn("1000");
    $('.two-way-search-box .dropdown').css('display', 'block');
    $('.two-way-search-box .dropdown-2').css('display', 'none');
});

$('.two-way-search-box .title-left').click(function () {
    $(this).css('border-bottom-color', '#FFAA00');

    $('.title-right').css('border-bottom-color', '#3d9c12');
    $('.title-left').css('color', '#FFAA00');
    $('.title-right').css('color', '#1a5302');

    $('.body').css('display', 'none').fadeIn("1000");
    $('.two-way-search-box .dropdown').css('display', 'none');
    $('.two-way-search-box .dropdown-2').css('display', 'block');
});
////////////////End Switch between search tabs////////////////

$('.home__banners').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    pauseOnHover: true,
    pauseOnFocus: true,
    dots: true,
    infinite: true,
    centerMode: true,
    rtl: true
});


//--------------------------------End Home page-------------------------//
