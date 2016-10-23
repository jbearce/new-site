// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// toggle an overlay when a [data-overlay] is clicked
jQuery("[data-overlay]:not([aria-hidden])").click(function (e) {
    e.preventDefault();

    var target_overlay = jQuery(this).data("overlay");

    if (!jQuery(this).hasClass("is-active")) {
        jQuery("[data-overlay]").removeClass("is-active");
        jQuery("[data-overlay=" + target_overlay + "]").addClass("is-active");
        jQuery("[data-overlay=" + target_overlay + "][aria-hidden]").attr("aria-hidden", "false");
        jQuery("[data-overlay=" + target_overlay + "][aria-hidden]").focus();
        // jQuery("[data-overlay=" + target_overlay + "][aria-hidden], .overlay-closer").trap();
        jQuery(".overlay-closer").addClass("is-active");
        jQuery(".overlay-closer").attr("aria-hidden", "false");
    } else {
        var closing_overlay = jQuery("[data-overlay][aria-hidden=false]").data("overlay");

        jQuery("[data-overlay=" + closing_overlay + "]").not("[aria-hidden]").focus();
        jQuery("[data-overlay]").removeClass("is-active");
        // jQuery("[data-overlay]").untrap();
        jQuery("[data-overlay][aria-hidden]").attr("aria-hidden", "true");
        jQuery(".overlay-closer").removeClass("is-active");
        jQuery(".overlay-closer").attr("aria-hidden", "true");
    }
});
