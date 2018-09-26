// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Slideout from "slideout";
import debounce from "debounce";
import focusTrap from "focus-trap";

// get the elements
const PANEL  = document.getElementById("page-container");
const MENU   = document.getElementById("mobile-menu");
const TOGGLE = document.querySelector("[data-toggle=mobile-menu]");

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

    const FOCUS_TRAP = focusTrap("#" + MENU.id, {
        clickOutsideDeactivates: true,
    });

    const GET_SLIDEOUT = () => {
        return new Slideout({
            duration:   250,
            itemToMove: "menu",
            menu:       MENU,
            panel:      PANEL,
        });
    };

    const CONSTRUCT_SLIDEOUT = () => {
        // construct the menu
        mobile_menu = GET_SLIDEOUT();

        // trap focus on open
        mobile_menu.on("open", () => {
            MENU.focus();
            FOCUS_TRAP.activate();
        });

        // release focus on close
        mobile_menu.on("close", () => {
            TOGGLE.focus();
            FOCUS_TRAP.deactivate();
        });
    };

    const DESTROY_SLIDEOUT = () => {
        // untrap the focus from the mobile menu
        FOCUS_TRAP.deactivate();

        // destroy the menu
        mobile_menu.destroy();

        // reset to null to ensure 'else' works correctly
        mobile_menu = null;
    };

    const UPDATE_MENU_STATE = () => {
        const MENU_DISPLAY = getComputedStyle(MENU).display;

        // destroy the menu when it's set to display: none
        if (mobile_menu !== null && MENU_DISPLAY === "none") {
            DESTROY_SLIDEOUT();
        // construct the menu when it's not set to display: none
        } else if (mobile_menu === null && MENU_DISPLAY !== "none") {
            CONSTRUCT_SLIDEOUT();
        }
    };

    // destroy the menu on desktop
    window.addEventListener("load", () => {
        if (mobile_menu === null && getComputedStyle(MENU).display !== "none") {
            CONSTRUCT_SLIDEOUT();
        }
    });

    // construct or destroy the menu based on browser width
    window.onresize = debounce(UPDATE_MENU_STATE, 200);
}
