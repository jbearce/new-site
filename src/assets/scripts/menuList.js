// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

/*
 * GOALS:
 *
 * Change aria attributes where appropriate.
 *
 * In the instance of an -overlay style menu, this is when
 * .menu-list.-overlay parentNode .menu-list_item.-parent or its children are
 * moused in or out of.
 *
 * For an -accordion style menu, this would be when .menu-list.-accordion
 * parentNode .menu-list_link is clicked on, or when another .menu-list_link
 * with children is clicked on, excluding children of the currently active menu.
 *
 * Toggle is-active state when associated .menu-list_toggle is clicked. When
 * the active menu (and its children) lose focus, remove the is-active state
 *
 * Ideally, this would all be in vanilla JavaScript, but I'm using jQuery for
 * the time being.
 *
 */

// toggle the is-active class and aria values on button click
jQuery(".menu-list_toggle").bind("click", function(e) {
    e.preventDefault();

    if (jQuery(this).next(".menu-list[aria-hidden]").attr("aria-hidden") === "true") {
        // mark all sibling menus as inactive
        jQuery(this).closest(".menu-list_item").siblings().removeClass("is-active");

        // mark menu list as active
        jQuery(this).closest(".menu-list_item").addClass("is-active");
        jQuery(this).next(".menu-list[aria-hidden]").attr("aria-hidden", "false");
    } else {
        // mark menu list as inactive
        jQuery(this).closest(".menu-list_item").removeClass("is-active");
        jQuery(this).next(".menu-list[aria-hidden]").attr("aria-hidden", "true");
    }
});

// remove is-active and aria values on blur
jQuery(".menu-container").on("blur", ".menu-list_link", function() {
    var this_parent = jQuery(this).closest(".menu-list_item");
    var this_grand_parent = this_parent.closest(".menu-list");
    var this_great_grand_parent = this_grand_parent.closest(".menu-list_item");

    if (this_parent.is(":last-child")) {
        if(!this_grand_parent.is(".menu-container > .menu-list")) {
            this_grand_parent.attr("aria-hidden", "true");
        }

        this_great_grand_parent.removeClass("is-active");
    }
});
