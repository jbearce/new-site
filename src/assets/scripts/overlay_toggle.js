// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

/* jshint -W083 */

var i                = 0,
    overlay_buttons  = document.querySelectorAll("[data-overlay]:not([aria-hidden])"),
    overlays         = document.querySelectorAll("[data-overlay]"),
    overlay_closers  = document.querySelectorAll(".overlay-closer");


// listen for clicks on all overlay buttons
for (i = 0; i < overlay_buttons.length; i++) {
    overlay_buttons[i].addEventListener("click", function (e) {
        e.preventDefault();

        if (!this.classList.contains("is-active")) {
            mark_overlay_active(this.dataset.overlay);
        } else {
            mark_overlay_inactive(this.dataset.overlay);
        }
    });
}

// function to open an overlay
function mark_overlay_active(target_overlay) {
    target_overlay  = (typeof target_overlay !== "undefined") ? target_overlay : false;

    // make a target overlay was set
    if (target_overlay !== false) {
        var target_overlays = document.querySelectorAll("[data-overlay=" + target_overlay + "]");

        // mark all overlays as inactive
        for (i = 0; i < overlays.length; i++) {
            overlays[i].classList.remove("is-active");

            // if it has an aria-hidden, change it to true
            if (overlays[i].hasAttribute("aria-hidden")) {
                overlays[i].setAttribute("aria-hidden", "true");
            }
        }

        // mark target overlay as active
        for (i = 0; i < target_overlays.length; i++) {
            target_overlays[i].classList.add("is-active");

            // if it has an aria-hidden, change it to false
            if (target_overlays[i].hasAttribute("aria-hidden")) {
                target_overlays[i].setAttribute("aria-hidden", "false");
                target_overlays[i].focus();
            }
        }

        // show the overlay closer
        for (i = 0; i < overlay_closers.length; i++) {
            overlay_closers[i].classList.add("is-active");
            overlay_closers[i].setAttribute("aria-hidden", "false");
        }
    }
}

// function to close an overlay
function mark_overlay_inactive(target_overlay) {
    target_overlay = (typeof target_overlay !== "undefined") ? closing_overlay : false;

    // make sure a target overlay was set
    if (target_overlay !== false) {
        var target_overlays = document.querySelectorAll("[data-overlay=" + target_overlay + "]");

        // mark all overlays as inactive
        for (i = 0; i < overlays.length; i++) {
            overlays[i].classList.remove("is-active");

            // if it has an aria-hidden, change it to true
            if (overlays[i].hasAttribute("aria-hidden")) {
                overlays[i].setAttribute("aria-hidden", "true");
            }
        }

        // focus the target overlay button
        for (i = 0; i < target_overlays.length; i++) {
            // if it does not have an aria-hidden, false focus it
            if (!target_overlays[i].hasAttribute("aria-hidden")) {
                target_overlays[i].focus();
            }
        }

        // hide the overlay closer
        for (i = 0; i < overlay_closers.length; i++) {
            overlay_closers[i].classList.remove("is-active");
            overlay_closers[i].setAttribute("aria-hidden", "true");
        }
    }
}
