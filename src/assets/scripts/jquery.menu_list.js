// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// open on button click
jQuery(".menu-list_toggle").click(function (e) {
    e.preventDefault();

    var target_menu = jQuery(this).next(".menu-list[aria-hidden]");
    var parent_item = jQuery(this).closest(".menu-list_item.-parent");

    if (target_menu.attr("aria-hidden") === "true") {
        // mark siblings as inactive
        parent_item.siblings(".menu-list_item.-parent").removeClass("is-active");
        parent_item.siblings(".menu-list_item.-parent").find(".menu-list_item.-parent.is-active").removeClass("is-active");
        parent_item.siblings(".menu-list_item.-parent").find(".menu-list[aria-hidden]").attr("aria-hidden", "true");
        parent_item.addClass("is-active");
        // mark target as active
        target_menu.attr("aria-hidden", "false");
        target_menu.find(".menu-list_link").first().focus(); // easy hack for "close on click away"
    } else {
        // mark all as inactive
        parent_item.find(".menu-list_item.-parent.is-active").removeClass("is-active");
        parent_item.find(".menu-list[aria-hidden]").attr("aria-hidden", "true");
        parent_item.removeClass("is-active");
        target_menu.attr("aria-hidden", "true");
    }
});

// open on hover
jQuery(".menu-list_item.-parent").hover(function () {
    var target_menu = jQuery(this).children(".menu-list[aria-hidden]");

    if (!target_menu.closest(".-accordion").length) {
        target_menu.attr("aria-hidden", "false");
    }
}, function () {
    var target_menu = jQuery(this).children(".menu-list[aria-hidden]");

    if (!target_menu.closest(".-accordion").length) {
        target_menu.attr("aria-hidden", "true");
    }
});

// WIP: handle keyboard navigation
jQuery(".menu-list").on("focusout", ".menu-list_link", function() {
    var parent_item = jQuery(this).closest(".menu-list_item.-parent");
    var target_menu = parent_item.children(".menu-list[aria-hidden]");

    if (parent_item.length) {
        // timeout required for the next element to get focus
        setTimeout(function() {
            if (!parent_item.find(":focus").length) {
                parent_item.removeClass("is-active");
                target_menu.attr("aria-hidden", "true");
            }
        }, 10);
    }
});
