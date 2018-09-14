// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import SuperSlide from "superslide.js";

// get the elements
const CONTENT    = document.getElementById("page-container");
const SLIDER     = document.getElementById("mobile-menu");
const TOGGLE     = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (CONTENT !== null && SLIDER !== null && TOGGLE !== null) {
    // initialize the menu
    const MOBILE_MENU = new SuperSlide({
        allowContentInteraction: false,
        animation:               "slideLeft",
        closeOnBlur:             true,
        content:                 document.getElementById("page-container"),
        duration:                0.25,
        slideContent:            false,
        slider:                  document.getElementById("mobile-menu"),
        onOpen:                  () => {
            SLIDER.setAttribute("aria-hidden", false);
        },
        onClose:                 () => {
            SLIDER.setAttribute("aria-hidden", true);
        },
    });

    // open the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();

        console.log(MOBILE_MENU.isOpen());

        if (!MOBILE_MENU.isOpen()) {
            MOBILE_MENU.open();
        } else {
            MOBILE_MENU.close();
        }
    });
}
