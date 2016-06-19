// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// close the overlay and any associated elements when overlay-closer is clicked
document.querySelector(".overlay-closer").addEventListener("click", function () {
    // mark it as inactive
    this.classList.remove("is-active");

    // get all the menus
    var menus = document.querySelectorAll("[data-menu]");

    // loop through each menu
    Array.from(menus).forEach(function(item) {
        // mark it as inactive if it's not
        item.classList.remove("is-active");

        // change the aria value for accessibility
        if (item.hasAttribute("aria-hidden"))
            item.setAttribute("aria-hidden", "true");
    });
});
