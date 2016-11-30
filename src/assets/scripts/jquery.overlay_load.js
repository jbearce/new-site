// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// open an overlay when a the page finishes loading
jQuery(window).on("load hashchange", function() {
    var hash = window.location.hash.substring(1);

    if (hash) {
        var overlays = jQuery("[data-overlay=" + hash + "]");

        if (overlays.length > 0) {
            jQuery("[data-overlay]").not("[data-overlay=" + hash + "]").removeClass("is-active");
            jQuery("[data-overlay][aria-hidden]").not("[data-overlay=" + hash + "]").attr("aria-hidden", "true");

            jQuery(overlays).each(function() {
                jQuery(this).addClass("is-active");

                if (jQuery(this).is("[aria-hidden]")) jQuery(this).attr("aria-hidden", "false").first().focus();
            });

            jQuery(".overlay-closer").addClass("is-active").attr("aria-hidden", "false");
        }
    }
});
