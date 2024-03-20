'use strict';

//------------------------------Start Settings page-----------------------//

/////////////////////// Start Script for change profile pic ////////////////
// Trigger for onclick redirect to input file
$('#OpenImgUpload').click(function () {
    $('#imgupload').trigger('click');
});
/////////////////////// End Script for change profile pic //////////////////

////////////////// Start Script for Edit Information ////////////////////

// if clicked to change any information show submit button
$('#name-edit, #email-edit, #date-edit').click(function () {
    $("#submit").css("display", "block");
});

// if clicked to change Name
$('#name-edit').click(function () {
    $("#username").attr("disabled", false).focus();
    $("#name-box").css("border-color", "#F2D383");
    $("#name-edit").css("display", "none");

});

// if clicked to change Email
$('#email-edit').click(function () {
    $("#email").attr("disabled", false).focus();
    $("#email-box").css("border-color", "#F2D383");
    $("#email-edit").css("display", "none");

});

// if clicked to change Date
$('#date-edit').click(function () {
    $("#date").attr("disabled", false).attr("type", "date").css("width", "auto").trigger('click').focus();
    $("#date-box").css("border-color", "#F2D383");
    $("#date-edit").css("display", "none");
});
////////////////// End Script for Edit Information ////////////////////

//------------------------------End Settings page------------------------//