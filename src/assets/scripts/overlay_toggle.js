// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

/* jshint -W083 */

var i                    = 0,
    overlay_elems        = document.querySelectorAll("[data-overlay]:not([aria-hidden])"),
    all_overlay_elems    = document.querySelectorAll("[data-overlay]"),
    overlay_closer_elems = document.querySelectorAll(".overlay-closer");


// listen for clicks on all overlay buttons
for (i = 0; i < overlay_elems.length; i++) {
    overlay_elems[i].addEventListener("click", function (e) {
        e.preventDefault();

        if (!this.classList.contains("is-active")) {
            open_overlay(this.dataset.overlay);
        } else {
            close_overlay(this.dataset.overlay);
        }
    });
}

// function to open an overlay
function open_overlay(target_overlay) {
    target_overlay  = (typeof target_overlay !== "undefined") ? target_overlay : false;

    // make a target overlay was set
    if (target_overlay !== false) {
        var target_overlay_elems = document.querySelectorAll("[data-overlay=" + target_overlay + "]");

        // mark all overlays as inactive
        for (i = 0; i < all_overlay_elems.length; i++) {
            all_overlay_elems[i].classList.remove("is-active");

            // if it has an aria-hidden, change it to true
            if (all_overlay_elems[i].hasAttribute("aria-hidden")) {
                all_overlay_elems[i].setAttribute("aria-hidden", "true");
            }
        }

        // mark target overlay as active
        for (i = 0; i < target_overlay_elems.length; i++) {
            target_overlay_elems[i].classList.add("is-active");

            // if it has an aria-hidden, change it to false
            if (target_overlay_elems[i].hasAttribute("aria-hidden")) {
                target_overlay_elems[i].setAttribute("aria-hidden", "false");
                target_overlay_elems[i].focus();
            }
        }

        // show the overlay closer
        for (i = 0; i < overlay_closer_elems.length; i++) {
            overlay_closer_elems[i].classList.add("is-active");
            overlay_closer_elems[i].setAttribute("aria-hidden", "false");
        }
    }
}

// function to close an overlay
function close_overlay(target_overlay) {
    target_overlay = (typeof target_overlay !== "undefined") ? closing_overlay : false;

    // make sure a target overlay was set
    if (target_overlay !== false) {
        var target_overlay_elems = document.querySelectorAll("[data-overlay=" + target_overlay + "]");

        // mark all overlays as inactive
        for (i = 0; i < all_overlay_elems.length; i++) {
            all_overlay_elems[i].classList.remove("is-active");

            // if it has an aria-hidden, change it to true
            if (all_overlay_elems[i].hasAttribute("aria-hidden")) {
                all_overlay_elems[i].setAttribute("aria-hidden", "true");
            }
        }

        // focus the target overlay button
        for (i = 0; i < target_overlay_elems.length; i++) {
            // if it does not have an aria-hidden, false focus it
            if (!target_overlay_elems[i].hasAttribute("aria-hidden")) {
                target_overlay_elems[i].focus();
            }
        }

        // hide the overlay closer
        for (i = 0; i < overlay_closer_elems.length; i++) {
            overlay_closer_elems[i].classList.remove("is-active");
            overlay_closer_elems[i].setAttribute("aria-hidden", "true");
        }
    }
}
