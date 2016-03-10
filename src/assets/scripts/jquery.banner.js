// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

$(".banner-wrapper").each(function() {
    // get the name of the cookie
    var cookieName = $(this).data("banner");

    // check if the cookie is set
    if (document.cookie.indexOf(cookieName) === -1) {
        // show the banner if the cookie doesn't exist
        $(this).slideDown(500, function() {
            // adjust the hidden value after the animation completes
            $(this).removeAttr("hidden");
        });
    }

    // handle button clicks
    $(this).find(".banner-button").click(function(e) {
        // hide the notification banner
        $(this).closest(".banner-wrapper").slideUp(250, function() {
            // set the cookie after the animation completes
            document.cookie = cookieName + "=yes";

            // adjust the hidden value after the animation completes
            $(this).attr("hidden", "hidden");
        });
    });
});
