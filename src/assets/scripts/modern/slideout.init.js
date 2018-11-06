// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

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

    // set up a focus trap
    const FOCUS_TRAP = focusTrap(`#${MENU.id}`, {
        clickOutsideDeactivates: true,
    });

    // create a new slideout instance
    const GET_SLIDEOUT = () => {
        return new Slideout({
            duration:   250,
            itemToMove: "menu",
            menu:       MENU,
            panel:      PANEL,
            padding:    280,
        });
    };

    // construct a slideout instance along with event hooks
    const CONSTRUCT_SLIDEOUT = () => {
        // get the slideout
        mobile_menu = GET_SLIDEOUT();

        // trap focus on open
        mobile_menu.on("open", () => {
            MENU.focus();
            FOCUS_TRAP.activate();
        });

        // release focus on close
        mobile_menu.on("close", () => {
            FOCUS_TRAP.deactivate();
        });
    };

    // completely destroy a slideout instance
    const DESTROY_SLIDEOUT = () => {
        // untrap the focus from the mobile menu
        FOCUS_TRAP.deactivate();

        // destroy the menu
        mobile_menu.destroy();

        // reset to null to ensure constructing on resize works correctly
        mobile_menu = null;
    };

    // create or destroy a slideout depending on menu display
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

    // create or destroy a slideout on load
    window.onload = UPDATE_MENU_STATE();

    // create or destroy a slideout on resize
    window.onresize = debounce(UPDATE_MENU_STATE, 200);
}
