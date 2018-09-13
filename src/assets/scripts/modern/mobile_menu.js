// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

// Inspired by https://medium.com/outsystems-experts/gestures-glamour-setting-up-a-touch-menu-6d9b94039997#.d2r8ubylw

import focusTrap from "focus-trap";

document.addEventListener("DOMContentLoaded", () => {
    const MENU_CONTAINER      = document.querySelector(".navigation-block.-flyout");
    const MENU                = document.querySelector(".navigation-block.-flyout .navigation_inner");
    const OVERLAY             = document.querySelector(".navigation-block.-flyout .navigation_background");
    const TOGGLE              = document.querySelector(".menu-toggle");
    const ACTIVE_CLASS        = "is-active";
    const TRANSITIONING_CLASS = "is-transitioning";
    const MAX_OPACITY         = 0.5; // if changed, don't forget to change opacity in css
    const VELOCITY            = 0.3;

    let trackable_element     = false;
    let touching_element      = false;
    let start_time            = 0;
    let start_coords          = [0, 0];
    let current_coords        = [0, 0];

    let is_open               = false;
    let is_moving             = false;
    let menu_width            = 0;
    let last_coords           = [0, 0];
    let move_x                = 0; // where the menu currently
    let drag_direction        = "";

    const FOCUS_TRAPPER = focusTrap(".navigation-block.-flyout");

    const CLOSE_MENU = (translate_x) => {
        FOCUS_TRAPPER.deactivate();

        const ON_TRANSITION_END = () => {
            OVERLAY.style.opacity = "";

            MENU_CONTAINER.removeEventListener("transitionend", ON_TRANSITION_END, false);
        };

        if (translate_x < 0 || !is_open) {
            MENU.style.transform = "";
            MENU.style.webkitTransform = "";

            OVERLAY.classList.remove(ACTIVE_CLASS);

            MENU_CONTAINER.setAttribute("aria-hidden", "true");
            MENU_CONTAINER.classList.remove(ACTIVE_CLASS);
            MENU_CONTAINER.addEventListener("transitionend", ON_TRANSITION_END, false);
        }
    };

    const OPEN_MENU = () => {
        MENU.style.transform = "";
        MENU.style.webkitTransform = "";

        MENU_CONTAINER.classList.add(ACTIVE_CLASS);

        OVERLAY.classList.add(ACTIVE_CLASS);
        OVERLAY.style.opacity = "";

        FOCUS_TRAPPER.activate();

        MENU_CONTAINER.setAttribute("aria-hidden", "false");
    };

    const CLOSE_MENU_overlay = () => {
        FOCUS_TRAPPER.deactivate();

        const ON_TRANSITION_END = () => {
            OVERLAY.classList.remove(ACTIVE_CLASS);
            MENU_CONTAINER.removeEventListener("transitionend", ON_TRANSITION_END);
        };

        MENU_CONTAINER.addEventListener("transitionend", ON_TRANSITION_END, false);
        MENU_CONTAINER.setAttribute("aria-hidden", "true");
        MENU_CONTAINER.classList.remove(ACTIVE_CLASS);
    };

    const CLICK_OPEN_MENU = () => {
        OVERLAY.classList.add(ACTIVE_CLASS);

        requestAnimationFrame(() => {
            setTimeout(() => {
                MENU_CONTAINER.setAttribute("aria-hidden", "false");
                MENU_CONTAINER.classList.add(ACTIVE_CLASS);
                MENU_CONTAINER.focus();
                FOCUS_TRAPPER.activate();
            }, 1);
        });
    };

    const UPDATE_UI = () => {
        if (is_moving) {
            MENU.style.transform = "translateX(" + move_x + "px)";
            MENU.style.webkitTransform = "translateX(" + move_x + "px)";

            requestAnimationFrame(UPDATE_UI);
        }
    };

    const TOGGLE_TRANSITIONS = (elem, mode = 0) => {
        if (mode === 1) {
            elem.style.transition = "";
        } else {
            elem.style.transition = "none";
        }
    };

    const TOUCH_START = (start_coords) => {
        const MENU_OPEN = MENU_CONTAINER.classList.contains(ACTIVE_CLASS);

        if (MENU_OPEN !== false) {
            is_open = true;
        } else {
            is_open = false;
        }

        // disable transitions
        TOGGLE_TRANSITIONS(MENU_CONTAINER);
        TOGGLE_TRANSITIONS(MENU);

        is_moving   = true;
        menu_width  = MENU.offsetWidth;
        last_coords = start_coords;

        if (is_open) {
            move_x = 0;
        } else {
            move_x = -menu_width;
        }

        MENU_CONTAINER.classList.add(TRANSITIONING_CLASS);

        OVERLAY.classList.add(ACTIVE_CLASS);

        drag_direction = "";
    };

    const TOUCH_MOVE = (event, current_coords, TRANSLATE_COORDS) => {
        if (!drag_direction) {
            if (Math.abs(TRANSLATE_COORDS[0]) >= Math.abs(TRANSLATE_COORDS[1])) {
                drag_direction = "horizontal";
            } else {
                drag_direction = "vertical";
            }

            requestAnimationFrame(UPDATE_UI); // this is what effectively does the animation (ﾉ◕ヮ◕)ﾉ*:・ﾟ✧
        }
        if (drag_direction === "vertical") {
            last_coords = current_coords;
        } else {
            event.preventDefault();

            if (move_x + (current_coords[0] - last_coords[0]) < 0 && move_x + (current_coords[0] - last_coords[0]) > -menu_width) {
                move_x = move_x + (current_coords[0] - last_coords[0]);
            }

            last_coords = current_coords;

            // disable transitions
            TOGGLE_TRANSITIONS(OVERLAY);

            const NEW_OPACITY = (((MAX_OPACITY) * (100 - ((Math.abs(move_x) * 100) / menu_width))) / 100);

            if (OVERLAY.style.opacity !== NEW_OPACITY.toFixed(2) && NEW_OPACITY.toFixed(1) % 1 !== 0) {
                OVERLAY.style.opacity = NEW_OPACITY.toFixed(2);
            }
        }
    };

    const TOUCH_END = (current_coords, TRANSLATE_COORDS, TIME_TAKEN) => {
        is_moving = false;

        if (current_coords === [0, 0]) {
            // enable transitions
            TOGGLE_TRANSITIONS(MENU_CONTAINER, 1);

            if (is_open) {
                TOGGLE_TRANSITIONS(MENU, 1);
            } else {
                TOGGLE_TRANSITIONS(OVERLAY, 1);
            }
        } else {
            if (is_open) {
                if ((TRANSLATE_COORDS[0] < (-menu_width) / 2) || (Math.abs(TRANSLATE_COORDS[0]) / TIME_TAKEN > VELOCITY)) {
                    CLOSE_MENU(TRANSLATE_COORDS[0]);
                    is_open = false;
                } else {
                    OPEN_MENU();
                    is_open = true;
                }
            } else {
                if (TRANSLATE_COORDS[0] > menu_width / 2 || (Math.abs(TRANSLATE_COORDS[0]) / TIME_TAKEN > VELOCITY)) {
                    OPEN_MENU();
                    is_open = true;
                } else {
                    CLOSE_MENU(TRANSLATE_COORDS[0]);
                    is_open = false;
                }

            }
        }

        MENU_CONTAINER.classList.remove(TRANSITIONING_CLASS);

        // enable transitions
        TOGGLE_TRANSITIONS(MENU_CONTAINER, 1);
        TOGGLE_TRANSITIONS(MENU, 1);
        TOGGLE_TRANSITIONS(OVERLAY, 1);
    };

    const ON_TOUCH_START = (event) => {
        let element      = document.elementFromPoint(event.changedTouches[0].clientX, event.changedTouches[0].clientY);
        let is_clickable = false;

        if (element) {
            while (element.parentNode) {
                if ((element.tagName === "A" || element.tagName === "BUTTON" || element.tagName === "INPUT") && element != OVERLAY) {
                    is_clickable = true; return;
                }

                element = element.parentNode;
            }
        }

        if (is_clickable) {
            return;
        }

        start_time = new Date().getTime();
        start_coords = [event.touches[0].pageX, event.touches[0].pageY];

        touching_element = true;

        TOUCH_START(start_coords);
    };

    const ON_TOUCH_MOVE = (event) => {
        let element      = document.elementFromPoint(event.changedTouches[0].clientX, event.changedTouches[0].clientY);
        let is_clickable = false;

        if (element) {
            while (element.parentNode) {
                if ((element.tagName === "A" || element.tagName === "BUTTON" || element.tagName === "INPUT") && element != OVERLAY) {
                    is_clickable = true; return;
                }

                element = element.parentNode;
            }
        }

        if (!touching_element || is_clickable) {
            return;
        }

        current_coords          = [event.touches[0].pageX, event.touches[0].pageY];
        const TRANSLATE_COORDS  = [(current_coords[0] - start_coords[0]), (current_coords[1] - start_coords[1])];

        TOUCH_MOVE(event, current_coords, TRANSLATE_COORDS);
    };

    const ON_TOUCH_END = (event) => {
        let element      = document.elementFromPoint(event.changedTouches[0].clientX, event.changedTouches[0].clientY);
        let is_clickable = false;

        if (element) {
            while (element.parentNode) {
                if ((element.tagName === "A" || element.tagName === "BUTTON" || element.tagName === "INPUT") && element != OVERLAY) {
                    is_clickable = true; return;
                }

                element = element.parentNode;
            }
        }

        if (!touching_element || is_clickable) {
            return;
        }

        touching_element = false;

        const TRANSLATE_COORDS = [(current_coords[0] - start_coords[0]), (current_coords[1] - start_coords[1])];

        const TIME_TAKEN = (new Date().getTime() - start_time);

        TOUCH_END(current_coords, TRANSLATE_COORDS, TIME_TAKEN);
    };

    const INIT = (element) => {
        trackable_element = element;

        start_time = new Date().getTime(); // start time of the touch

        trackable_element.addEventListener("touchstart", ON_TOUCH_START, false);
        trackable_element.addEventListener("touchmove", ON_TOUCH_MOVE, false);
        trackable_element.addEventListener("touchend", ON_TOUCH_END, false);

        OVERLAY.addEventListener("click", CLOSE_MENU_overlay, false); // click the overlay to immediately close the menu
        TOGGLE.addEventListener("click", CLICK_OPEN_MENU, false);     // click the toggle to immediately open the menu
    };

    INIT(MENU);
});
