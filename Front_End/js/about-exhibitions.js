"use strict";

let isInitialized = false;

///////////////////// Start code For Initialized popup-slider and start from image that user clicked on it /////////////////
function sendIndexToPopUp(imageIndex) {
    console.log(imageIndex);
    //Check if slick popup-slider is Initialized before, then destroy it, because it will throw Error
    if (isInitialized) {
        $('.popup-slider').slick('unslick');
    }
    //if slick popup-slider is Initialized put variable isInitialized to true, to check it in next time
    $('.popup-slider').on('init', function (event, slick) {
        isInitialized = true;
    });
    //We Initializing slick popup-slider here to give it the number of (initialSlide) of the image that user clicked on it 
    $('.popup-slider').slick({
        dots: false,
        infinite: true,
        autoplay: true,
        slidesToShow: 1,
        adaptiveHeight: true,
        draggable: true,
        arrows: true,
        slideIndex: imageIndex - 1
    });
}


///////////////////// Start code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////
$('.logo-holder').on('click', function () {
    window.setTimeout(function (event) {
        $('.popup-slider')[0].slick.setPosition();
    }, 500);
});
///////////////////// End code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////


let container = $("#popup-overlay");

// open and close popup regularly
$(document).ready(function () {
    $('#trigger-1').click(function () {
        container.fadeIn(300);
    });
    $('#trigger-2').click(function () {
        container.fadeIn(300);
    });

    $('#close').click(function () {
        container.fadeOut(300);
    });
    // $('.select2').select2({
    //     dir: 'rtl',
    //     language: 'ar',
    //     allowClear: false,
    // });
});

// close popup if click away from it
$(document).mouseup(function (e) {
    if (container.has(e.target).length === 0) {
        container.fadeOut(300);
    }
});

