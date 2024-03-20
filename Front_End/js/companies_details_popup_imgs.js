"use strict";

// Gallery All Images ..
// let imgArray = ['../images/image_1.jpg', '../images/image_2.jpg', '../images/image_3.jpg', '../images/image_4.jpg',
//     '../images/image_5.jpg', '../images/image_6.jpg', '../images/image_7.jpg', '../images/image_8.jpg', '../images/image_9.jpg', '../images/image_10.jpg'];
let isInitialized = false;

////////////////////////////////////////// Start code For generating //////////////////////////////////////////////////
// (function () {
//     for (let index = 0; index < imgArray.length; index++) {

//generating images inside gallery-slider
// let divHolder = document.createElement('div');
// divHolder.className = "item";
// divHolder.innerHTML =
//     `<a data-toggle="modal" data-target="#gallery-big-slider" class="logo-holder" onClick="sendIndexToPopUp(${index})">
//         <img src="${imgArray[index]}" alt="partner logo">
//         بادي نامي
//     </a>`;
// document.getElementById("gallery-slider").appendChild(divHolder);

//generating images inside modal-body-slides
//         let imgHolder = document.createElement('img');
//         imgHolder.className = "images-popups";
//         imgHolder.src = imgArray[index];
//         document.getElementById("modal-body-slides").appendChild(imgHolder);
//     }
// }());
////////////////////////////////////////// End code For generating ////////////////////////////////////////////////////

///////////////////// Start code For Initialized popup-slider and start from image that user clicked on it /////////////////
function sendIndexToPopUp(imageIndex) {
    //change images paths
    for (let index = 1; index < 6; index++) {
        $(`.images-popups-${index}`).attr("src", `../../images/show_${imageIndex}/image_${index}.jpg`);
    }
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
        arrows: true
    });
}

///////////////////// End code For Initialized popup-slider and start from image that user clicked on it /////////////////

///////////////////// Start code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////
$('.logo-holder').on('click', function () {
    window.setTimeout(function (event) {
        $('.popup-slider')[0].slick.setPosition();
    }, 500);
});
///////////////////// End code to setPosition for popup-slider after one second from clicking to start modal, because modal making problems /////////////////


