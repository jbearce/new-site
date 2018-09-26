// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Slideout from "slideout";
import debounce from "debounce";

// get the elements
const PANEL        = document.getElementById("page-container");
const MENU         = document.getElementById("mobile-menu");
const TOGGLE       = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (PANEL !== null && MENU !== null && TOGGLE !== null) {
    // initialize the menu
    let mobile_menu = null;

    // toggle the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (mobile_menu !== null) {
            mobile_menu.toggle();
        }
    });

    // close the menu when it's open and the content is clicked
    PANEL.addEventListener("click", (e) => {
        if (mobile_menu !== null && e.target !== TOGGLE && mobile_menu.isOpen()) {
            e.preventDefault();
            mobile_menu.close();
        }
    });

    const CONSTRUCT_MENU = () => {
        return new Slideout({
            duration:   250,
            itemToMove: "menu",
            menu:       MENU,
            panel:      PANEL,
        });
    };

    const UPDATE_MENU_STATE = () => {
        const MENU_DISPLAY = getComputedStyle(MENU).display;

        // destroy the menu when it's set to display: none
        if (mobile_menu !== null && MENU_DISPLAY === "none") {
            mobile_menu.destroy();
            // reset to null to ensure 'else' works correctly
            mobile_menu = null;
        // construct the menu when it's not set to display: none
        } else if (mobile_menu === null && MENU_DISPLAY !== "none") {
            mobile_menu = CONSTRUCT_MENU();
        }
    };

    // destroy the menu on desktop
    window.addEventListener("load", () => {
        if (mobile_menu === null && getComputedStyle(MENU).display !== "none") {
            mobile_menu = CONSTRUCT_MENU();
        }
    });

    // construct or destroy the menu based on browser width
    window.onresize = debounce(UPDATE_MENU_STATE, 200);
}
