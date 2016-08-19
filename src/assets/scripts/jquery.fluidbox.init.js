// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// init fluidbox
jQuery(function () {
    // toggle active class on body
    jQuery(".user-content a img").parent("a[href$='.gif'], a[href$='.jpg'], a[href$='.png'], a[href$='.bmp'], a[href$='.GIF'], a[href$='.JPG'], a[href$='.PNG'], a[href$='.BMP']").on("openstart.fluidbox", function() {
        jQuery("body").addClass("is-fluidboxactive");
    }).on("closeend.fluidbox", function() {
        jQuery("body").removeClass("is-fluidboxactive");
    }).fluidbox();
});
