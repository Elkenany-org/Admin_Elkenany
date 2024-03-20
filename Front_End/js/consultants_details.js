
let mainContainer = $("#popup-overlay");

// open and close popup regularly 
$(document).ready(function () {
    $('#trigger-1').click(function () {
        $(".popup-title").text($('#trigger-1-title').text());
        $(".popup-description").text($('#trigger-1-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-2').click(function () {
        $(".popup-title").text($('#trigger-2-title').text());
        $(".popup-description").text($('#trigger-2-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-3').click(function () {
        $(".popup-title").text($('#trigger-3-title').text());
        $(".popup-description").text($('#trigger-3-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-4').click(function () {
        $(".popup-title").text($('#trigger-4-title').text());
        $(".popup-description").text($('#trigger-4-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-5').click(function () {
        $(".popup-title").text($('#trigger-5-title').text());
        $(".popup-description").text($('#trigger-5-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-6').click(function () {
        $(".popup-title").text($('#trigger-6-title').text());
        $(".popup-description").text($('#trigger-6-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-7').click(function () {
        $(".popup-title").text($('#trigger-7-title').text());
        $(".popup-description").text($('#trigger-7-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-8').click(function () {
        $(".popup-title").text($('#trigger-8-title').text());
        $(".popup-description").text($('#trigger-8-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-9').click(function () {
        $(".popup-title").text($('#trigger-9-title').text());
        $(".popup-description").text($('#trigger-9-description').text());
        mainContainer.fadeIn(300);
    });
    $('#trigger-10').click(function () {
        $(".popup-title").text($('#trigger-10-title').text());
        $(".popup-description").text($('#trigger-10-description').text());
        mainContainer.fadeIn(300);
    });

    $('#close').click(function () {
        mainContainer.fadeOut(300);
    });
});

// close popup if click away from it
$(document).mouseup(function (e) {

    if (mainContainer.has(e.target).length === 0) {
        mainContainer.fadeOut(300);
    }
});