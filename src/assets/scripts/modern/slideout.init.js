// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Slideout from "slideout";

// get the elements
const PANEL        = document.getElementById("page-container");
const MENU         = document.getElementById("mobile-menu");
const TOGGLE       = document.querySelector("[data-toggle=mobile-menu]");
const MOBILE_WIDTH = 768; // @TODO consider switching to checking getComputedStyle(TOGGLE, null).display

// verify that the elements exist
if (PANEL !== null && MENU !== null && TOGGLE !== null) {
    // initialize the menu
    let mobile_menu = null;

    // toggle the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();

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

    // destroy the menu on desktop
    window.addEventListener("load", () => {
        if (mobile_menu === null && document.documentElement.clientWidth < MOBILE_WIDTH) {
            mobile_menu = CONSTRUCT_MENU();
        }
    });

    // destroy the menu on desktop
    window.addEventListener("resize", () => {
        const CLIENT_WIDTH = document.documentElement.clientWidth;

        if (mobile_menu !== null && CLIENT_WIDTH >= MOBILE_WIDTH) {
            mobile_menu.destroy();
            mobile_menu = null;
        } else if (mobile_menu === null && CLIENT_WIDTH < MOBILE_WIDTH) {
            mobile_menu = CONSTRUCT_MENU();
        }
    });
}
