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
