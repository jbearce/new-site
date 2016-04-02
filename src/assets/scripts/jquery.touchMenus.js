// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

/* open a drop down when a menu-link is clicked */

// check if a menu-link with a dropdown has been clicked
jQuery(".menu-link[aria-haspopup=true]").on({"touchstart" : function (e) {
    // check if it's been clicked already
    if (!jQuery(this).closest(".menu-item").hasClass("-active")) {
        // prevent the user from visiting the link
        e.preventDefault();

        // remove the active class from all siblings
        jQuery(this).closest(".menu-item").siblings(".menu-item.-active").removeClass("-active");

        // add the active class
        jQuery(this).closest(".menu-item").addClass("-active");
    }

    // when you click anywhere else on the document, close the menu
    jQuery(document).one("touchstart", function closeMenu (e) {
        // check if the user has clicked away from the menu
        if (jQuery(".menu-item.-active").has(e.target).length === 0) {
            // remove the active class from the drop down items
            jQuery(".menu-item.-active").removeClass("-active");
        } else {
            // trigger a click otherwise
            jQuery(document).one("click", closeMenu);
        }
    });
}});
