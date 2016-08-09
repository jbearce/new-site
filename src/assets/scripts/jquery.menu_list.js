// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// mark all menu items as inative
function mark_all_inactive(elem) {
    elem.find(".is-active").removeClass("is-active");
    elem.find("[aria-hidden=false]").attr("aria-hidden", "true");
}

// mark element as active
function mark_active(elem) {
    elem.closest(".menu-list_item").addClass("is-active");
    elem.siblings("[aria-hidden]").attr("aria-hidden", "false");
}

// mark menu as active when toggle is clicked
jQuery(".menu-list_toggle").click(function(e) {
    e.preventDefault();

    if (jQuery(this).next("[aria-hidden=true]").length) {
        mark_all_inactive(jQuery(this).closest(".menu-list"));
        mark_active(jQuery(this));
        jQuery(this).siblings("[aria-hidden]").find(".menu-list_link").first().focus(); // easy hack for "close on click away"
    } else {
        mark_all_inactive(jQuery(this).closest(".menu-list"));
    }
});

// open on hover
jQuery(".menu-list_item").not(".menu-list.-accordion .menu-list_item, .menu-list_item.-mega .menu-list_item.-parent").hover(function() {
    mark_all_inactive(jQuery(this).closest(".menu-list"));
    mark_active(jQuery(this).children(".menu-list_link"));
}, function () {
    mark_all_inactive(jQuery(this).closest(".menu-list"));
});

// open on touchstart
jQuery(".menu-list_item").not(".menu-list_item.-mega .menu-list_item.-parent").on("touchstart", function(e) {
    if (!jQuery(this).hasClass("is-active") && jQuery(this).children("[aria-hidden]").length) {
        e.preventDefault();
        mark_all_inactive(jQuery(this).closest(".menu-list"));
        mark_active(jQuery(this).children(".menu-list_link"));
    }
});

// hide on focusout
jQuery(".menu-list").on("focusout", ".menu-list_link", function() {
    var parent_item = jQuery(this).closest(".menu-list_item.-parent.is-active");

    if (parent_item.length) {
        // timeout required for the next element to get focus
        setTimeout(function() {
            if (!parent_item.find(":focus").length) {
                parent_item.removeClass("is-active");
                parent_item.children("[aria-hidden]").attr("aria-hidden", "true");
                parent_item.closest(".menu-list_item.-parent.is-active").find(".menu-list_link").first().trigger("focusout");
            }
        }, 10);
    }
});

// hide on touch away
jQuery(document).on("touchstart", function(e) {
    if (!jQuery(e.target).closest(".menu-list_item.-parent.is-active").length) {
        jQuery(".menu-list_item.-parent.is-active").children("[aria-hidden]").attr("aria-hidden", "true");
        jQuery(".menu-list_item.-parent.is-active").removeClass("is-active");
    }
});
