// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// fire toggle a menu when a menu-toggle is clicked
document.body.onclick = function (event) {
    var elem = event.target || event.toElement;

    if (elem.classList.contains("menu-toggle")) {
        // get the target menu
        var target_menu = target.dataset.menu;
        // get all elements related to the target menu
        var matched_elements = document.querySelectorAll("[data-menu=" + target_menu + "]");

        // loop through all matched elements
        Array.from(matched_elements).forEach(function(item) {
            // check if the menu is active
            if (!item.classList.contains("is-active")) {
                // mark it as active if it's not
                item.classList.add("is-active");

                // change the aria value for accessibility
                if (item.hasAttribute("aria-hidden"))
                item.setAttribute("aria-hidden", "false");

                // overlay the content
                if (!document.querySelector(".overlay-closer").classList.contains("is-active"))
                document.querySelector(".overlay-closer").classList.add("is-active");
                // check if the menu is inactive
            } else {
                // mark it as inactive if it's not
                item.classList.remove("is-active");

                // change the aria value for accessibility
                if (item.hasAttribute("aria-hidden"))
                item.setAttribute("aria-hidden", "true");

                // overlay the content
                if (document.querySelector(".overlay-closer").classList.contains("is-active"))
                document.querySelector(".overlay-closer").classList.remove("is-active");
            }
        });
    }
};
