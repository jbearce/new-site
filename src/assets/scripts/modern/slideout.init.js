// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Slideout from "slideout";

// get the elements
const PANEL = document.getElementById("page-container");
const MENU  = document.getElementById("mobile-menu");
const TOGGLE  = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (PANEL !== null && MENU !== null && TOGGLE !== null) {
    // initialize the menu
    const MOBILE_MENU = new Slideout({
        duration: 250,
        menu:     MENU,
        panel:    PANEL,
    });

    // toggle the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();
        MOBILE_MENU.toggle();
    });

    // close the menu when it's open and the content is clicked
    PANEL.addEventListener("click", (e) => {
        if (e.target !== TOGGLE && MOBILE_MENU.isOpen()) {
            e.preventDefault();
            MOBILE_MENU.close();
        }
    });
}
