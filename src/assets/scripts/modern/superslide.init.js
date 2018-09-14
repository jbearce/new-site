// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import OSREC from "superslide.js";

// get the elements
const CONTENT = document.getElementById("page-container");
const SLIDER  = document.getElementById("mobile-menu");
const TOGGLE  = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (CONTENT !== null && SLIDER !== null && TOGGLE !== null) {
    // initialize the menu
    const MOBILE_MENU = new OSREC.superslide({
        animation: "slideLeft",
        content:   document.getElementById("page-container"),
        slider:    document.getElementById("mobile-menu"),
    });

    // open the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();
        MOBILE_MENU.open();
    });
}
