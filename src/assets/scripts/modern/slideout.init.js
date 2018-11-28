// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import Slideout from "slideout";
import debounce from "debounce";
import focusTrap from "focus-trap";

// get the elements
const PANEL = document.getElementById("page-container");
const MENU = document.getElementById("mobile-menu");
const TOGGLE = document.querySelector("[data-toggle=mobile-menu]");

// verify that the elements exist
if (PANEL !== null && MENU !== null && TOGGLE !== null) {
    // initialize the menu
    let mobileMenu = null;

    // toggle the menu when clicking on the toggle
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (mobileMenu !== null) {
            mobileMenu.toggle();
        }
    });

    // close the menu when it's open and the content is clicked
    PANEL.addEventListener("click", (e) => {
        if (mobileMenu !== null && e.target !== TOGGLE && mobileMenu.isOpen()) {
            e.preventDefault();
            mobileMenu.close();
        }
    });

    // set up a focus trap
    const FOCUS_TRAP = focusTrap(`#${MENU.id}`, {
        clickOutsideDeactivates: true,
    });

    // create a new slideout instance
    const getSlideout = () => {
        return new Slideout({
            duration:   250,
            itemToMove: "menu",
            menu:       MENU,
            panel:      PANEL,
            padding:    280,
        });
    };

    // construct a slideout instance along with event hooks
    const constructSlideout = () => {
        // get the slideout
        mobileMenu = getSlideout();

        // trap focus on open
        mobileMenu.on("open", () => {
            MENU.focus();
            FOCUS_TRAP.activate();
        });

        // release focus on close
        mobileMenu.on("close", () => {
            FOCUS_TRAP.deactivate();
        });
    };

    // completely destroy a slideout instance
    const destroySlideout = () => {
        // untrap the focus from the mobile menu
        FOCUS_TRAP.deactivate();

        // destroy the menu
        mobileMenu.destroy();

        // reset to null to ensure constructing on resize works correctly
        mobileMenu = null;
    };

    // create or destroy a slideout depending on menu display
    const updateMenuState = () => {
        const MENU_DISPLAY = getComputedStyle(MENU).display;

        // destroy the menu when it's set to display: none
        if (mobileMenu !== null && MENU_DISPLAY === "none") {
            destroySlideout();
        // construct the menu when it's not set to display: none
        } else if (mobileMenu === null && MENU_DISPLAY !== "none") {
            constructSlideout();
        }
    };

    // create or destroy a slideout on load
    window.onload = updateMenuState();

    // create or destroy a slideout on resize
    window.onresize = debounce(updateMenuState, 200);
}
