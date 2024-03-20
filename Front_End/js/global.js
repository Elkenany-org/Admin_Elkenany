'use strict';

////////////////Start Loading Screen////////////////
// When the page loaded ..
$(window).on("load", function () {
    //Fade out spinner first in 2s after page loaded ..
    $(".chicken-loader").fadeOut(500,
        function () {
            //Reset body to overflow (auto) again after loading screen fade out ..
            $("body").css("overflow", "auto");

            //Fade out background 2s after spinner fade out ..
            $(this).parent().fadeOut(2000,
                function () {
                    //Remove loading screen from DOM tree
                    $(this).remove;
                });
        });
});
////////////////End Loading Screen////////////////

////////////////Start OF DropDown Navbar ////////////////
document.addEventListener("DOMContentLoaded", function () {

    // make it as accordion for smaller screens
    if (window.innerWidth < 992) {

        // close all inner dropdowns when parent is closed
        document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
            everydropdown.addEventListener('hidden.bs.dropdown', function () {
                // after dropdown is hidden, then find all submenus
                this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                    // hide every submenu as well
                    everysubmenu.style.display = 'none';
                });
            })
        });

        document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
            element.addEventListener('click', function (e) {

                let nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('submenu')) {
                    // prevent opening link if link needs to open dropdown
                    e.preventDefault();

                    if (nextEl.style.display == 'block') {
                        nextEl.style.display = 'none';
                    } else {
                        nextEl.style.display = 'block';
                    }

                }
            });
        })
    }
    // end if innerWidth

});
////////////////End OF DropDown Navbar ////////////////

////////////////Start Scroll to top////////////////
//Get the button
let mybutton = document.getElementById("myBtn");

// When the user scrolls down 20px from the top of the document, show the button
// window.onscroll = function () { scrollFunction() };
//
// function scrollFunction() {
//
//     //Script for button move to top ..
//     if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
//         mybutton.style.display = "block";
//     } else {
//         mybutton.style.display = "none";
//     }
// }
// When the user clicks on the button, scroll to the top of the document
// function topFunction() {
//     document.body.scrollTop = 0;
//     document.documentElement.scrollTop = 0;
// }
////////////////End Scroll to top////////////////

////////////////Start Show/Hide Accordion////////////////
$('#show-hide-accordion').click(function () {
    if (window.matchMedia('(max-width: 991.5px)').matches) {
        $('#accordion').slideToggle('fast');
    }
});
////////////////End Show/Hide Accordion////////////////


$(document).ready(function () {

    // Start Scroll To Top

    var offset = 300,
        duration = 1300;

    $(window).scroll(function () {

        if ($(this).scrollTop() > offset) {
            $('.btn_top').css({
                right: 10
            });
        } else {
            $('.btn_top').css({
                right: -60
            });
        }

        $('::-webkit-scrollbar').css('width', '50px');

    });

    $('.btn_top').click(function () {
        $('html, body').animate({scrollTop: 0});
    });

    // End Scroll To Top
    // $('select').niceSelect();
})

