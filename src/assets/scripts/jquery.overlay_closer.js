// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// close the overlay and any associated elements when overlay-closer is clicked
jQuery(".overlay-closer").click(function(e) {
    var closing_overlay = jQuery("[data-overlay][aria-hidden=false]").data("overlay");

    jQuery("[data-overlay=" + closing_overlay + "]").not("[aria-hidden]").focus();
    jQuery("[data-overlay]").removeClass("is-active");
    jQuery("[data-overlay][aria-hidden]").attr("aria-hidden", "true");
    jQuery(this).removeClass("is-active");
    jQuery(this).attr("aria-hidden", "true");
});

// close the overlay and any associated elements when escape key is pressed
jQuery(document).keyup(function(e) {
    if (jQuery("[data-overlay][aria-hidden=false]").length && e.keyCode === 27) {
        var closing_overlay = jQuery("[data-overlay][aria-hidden=false]").data("overlay");

        jQuery("[data-overlay=" + closing_overlay + "]").not("[aria-hidden]").focus();
        jQuery("[data-overlay]").removeClass("is-active");
        jQuery("[data-overlay][aria-hidden]").attr("aria-hidden", "true");
        jQuery(".overlay-closer").removeClass("is-active");
        jQuery(".overlay-closer").attr("aria-hidden", "true");
    }
});
