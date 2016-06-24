// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// close the overlay and any associated elements when overlay-closer is clicked
jQuery(".overlay-closer").click(function(e) {
    jQuery("[data-overlay]").removeClass("is-active");
    jQuery("[data-overlay]").attr("aria-hidden", "true");
    jQuery(this).removeClass("is-active");
    jQuery(this).attr("aria-hidden", "true");
});
