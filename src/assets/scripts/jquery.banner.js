// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

jQuery(".banner-container").each(function() {
    // get the name of the cookie
    var cookieName = jQuery(this).data("banner");

    // check if the cookie is set
    if (document.cookie.indexOf(cookieName) === -1) {
        // show the banner if the cookie doesn't exist
        jQuery(this).slideDown(500, function() {
            // adjust the hidden value after the animation completes
            jQuery(this).removeAttr("hidden");
        });
    }

    // handle button clicks
    jQuery(this).find(".banner-toggle").click(function(e) {
        // hide the notification banner
        jQuery(this).closest(".banner-container").slideUp(250, function() {
            // set the cookie after the animation completes
            document.cookie = cookieName + "=yes";

            // adjust the hidden value after the animation completes
            jQuery(this).attr("hidden", "hidden");
        });
    });
});
