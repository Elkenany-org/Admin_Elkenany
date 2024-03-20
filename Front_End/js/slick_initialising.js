// Start Partners Slider //
$('.partners-slider').slick({
    slidesToShow: 5,
    slidesToScroll: -1,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 1000,
    pauseOnHover: false,
    pauseOnFocus: false,
    dots: true,
    infinite: true,
    centerMode: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 1000,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 850,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 650,
            settings: {
                slidesToShow: 1,
                centerMode: false
            }
        }
    ],
    rtl: true
});
// End Partners Slider //

// Start Products Slider //
$('.products-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    pauseOnHover: false,
    pauseOnFocus: false,
    dots: true,
    infinite: true,
    centerMode: true,
    rtl: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 1000,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 770,
            settings: {
                slidesToShow: 1,
                centerMode: false
            }
        }
    ]
});
// End Products Slider //

// Start Market Slider //
$('.market-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    pauseOnHover: false,
    pauseOnFocus: false,
    dots: true,
    infinite: true,
    centerMode: true,
    rtl: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 1000,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 770,
            settings: {
                slidesToShow: 1,
                centerMode: false
            }
        }
    ]
});
// End Market Slider //

// Start Gallery Slider //
$('.gallery-slider').slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    pauseOnHover: false,
    pauseOnFocus: false,
    dots: true,
    infinite: true,
    centerMode: true,
    rtl: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 1000,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 770,
            settings: {
                slidesToShow: 1,
                centerMode: false
            }
        }
    ]
});
// End Gallery Slider //

// Start One Full Slider //
$('.one-full-slider').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    slidesToShow: 1,
    adaptiveHeight: true,
    pauseOnHover: true,
    pauseOnFocus: true,
    draggable: true,
    arrows: false,
    rtl: true,
}).click(function (e) {
    e.preventDefault();
});
$('.one-full-slider-banner').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    slidesToShow: 1,
    adaptiveHeight: true,
    pauseOnHover: true,
    pauseOnFocus: true,
    draggable: true,
    arrows: false,
    rtl: true,
});



// End One Full Slider //
