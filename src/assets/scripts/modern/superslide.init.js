// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import SuperSlide from "superslide.js";

// get the elements
const OVERLAY = document.getElementById("page-overlay");
const CONTENT = document.getElementById("page-container");
const SLIDER  = document.getElementById("mobile-menu");
const TOGGLE  = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (CONTENT !== null && SLIDER !== null && TOGGLE !== null) {
    // initialize the menu
    const MOBILE_MENU = new SuperSlide({
        animation:    "slideLeft",
        closeOnBlur:  true,
        content:      document.getElementById("page-container"),
        duration:     0.25,
        height:       "100vh",
        slideContent: false,
        slider:       document.getElementById("mobile-menu"),
        beforeOpen:   () => {
            OVERLAY.classList.remove("-transitioning");
            OVERLAY.classList.add("-active");
            OVERLAY.style.removeProperty("opacity");
        },
        beforeClose:   () => {
            CONTENT.classList.remove("-overlay");
            OVERLAY.classList.remove("-active");
        },
        onOpen:       () => {
            SLIDER.setAttribute("aria-hidden", false);
        },
        onClose:      () => {
            SLIDER.setAttribute("aria-hidden", true);
        },
        onDrag:       (completion) => {
            OVERLAY.classList.add("-transitioning");
            OVERLAY.style.opacity = completion / 2;
        }
    });

    // open the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();

        if (!MOBILE_MENU.isOpen()) {
            MOBILE_MENU.open();
        } else {
            MOBILE_MENU.close();
        }
    });
}
