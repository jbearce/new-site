// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import Slideout from "slideout";
import debounce from "debounce";
import focusTrap from "focus-trap";
import { disableBodyScroll, enableBodyScroll } from "body-scroll-lock";

// get the elements
const PANEL  = document.getElementById("page-container");
const MENU   = document.getElementById("mobile-menu");
const TOGGLE = document.querySelector("[data-toggle=mobile-menu]");

/**
 * Verify that elements exist
 */
if (PANEL !== null && MENU !== null && TOGGLE !== null) {
    /**
     * Set up a variable to hold the SlideOut instance
     */
    let mobile_menu = null;

    /**
     * Set up a focus trap
     */
    const FOCUS_TRAP = focusTrap(`#${MENU.id}`, {
        clickOutsideDeactivates: true,
    });

    /**
     * Function to create a new slideout instance
     */
    const GET_SLIDEOUT = () => {
        return new Slideout({
            duration:   250,
            itemToMove: "menu",
            menu:       MENU,
            panel:      PANEL,
            padding:    280,
        });
    };

    /**
     * Function to construct a slideout instance and apply event hooks
     */
    const CONSTRUCT_SLIDEOUT = () => {
        // get the slideout
        mobile_menu = GET_SLIDEOUT();

        mobile_menu.on("open", () => {
            /**
             * Focus the menu
             */
            MENU.focus();

            /**
             * Trap the focus
             */
            FOCUS_TRAP.activate();

            /**
             * Disable scrolling on the body
             */
            disableBodyScroll(MENU);

            /**
             * Disable touch interactions on the menu
             */
            mobile_menu.disableTouch();
        });

        mobile_menu.on("close", () => {
            /**
             * Release the focus
             */
            FOCUS_TRAP.deactivate();

            /**
             * Enable scrolling on the body
             */
            enableBodyScroll(MENU);

            /**
             * Enable touch interactions on the menu
             */
            mobile_menu.enableTouch();
        });
    };

    /**
     * Completely destroy a slideout instance
     */
    const DESTROY_SLIDEOUT = () => {
        /**
         * Release the focus
         */
        FOCUS_TRAP.deactivate();

        /**
         * Enable scrolling on the body
         */
        enableBodyScroll(MENU);

        /**
         * Destroy the slideout
         */
        mobile_menu.destroy();

        /**
         * Result the mobile menu to ensure constructing on resize works properly
         */
        mobile_menu = null;
    };

    /**
     * Create or destroy the SlideOut instance depending on the menu's display
     */
    const UPDATE_MENU_STATE = () => {
        const MENU_DISPLAY = getComputedStyle(MENU).display;

        /**
         * Destroy the SlideOut when it's display: none;
         */
        if (mobile_menu !== null && MENU_DISPLAY === "none") {
            DESTROY_SLIDEOUT();
        /**
         * Construct the SlideOut when it's nont display: none;
         */
        } else if (mobile_menu === null && MENU_DISPLAY !== "none") {
            CONSTRUCT_SLIDEOUT();
        }
    };

    /**
     * Toggle the slideout when clicking the menu icon
     */
    TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        if (mobile_menu !== null) {
            mobile_menu.toggle();
        }
    });

    /**
     * Close the menu when it's open and the content is clicked
     */
    PANEL.addEventListener("click", (e) => {
        if (mobile_menu !== null && e.target !== TOGGLE && mobile_menu.isOpen()) {
            e.preventDefault();

            mobile_menu.close();
        }
    });

    /**
     * Create or destroy the SlideOut on window load
     */
    window.onload = UPDATE_MENU_STATE();

    /**
     * Create or destroy the SlideOut on window resize
     */
    window.onresize = debounce(UPDATE_MENU_STATE, 200);
}
