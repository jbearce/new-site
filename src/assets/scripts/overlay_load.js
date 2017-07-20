// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

function overlay_load_init() {
    // function to open an overlay
    // @param  {String}  target_overlay - Name of the overlay to mark active
    function load_overlay(target_overlay) {
        if ((typeof target_overlay === "string")) {
            const overlays        = document.querySelectorAll("[data-overlay]");
            const overlay_closers = document.querySelectorAll(".overlay-closer");

            // make sure overlays exist
            if (overlays.length > 0) {
                for (let i = 0; i < overlays.length; i++) {
                    // check if the current overlay matches the target overlay
                    if (overlays[i].dataset.overlay === target_overlay) {
                        // mark the element as active
                        overlays[i].classList.add("is-active");

                        // mark the hidden element as unhidden, and focus it
                        if (overlays[i].hasAttribute("aria-hidden")) {
                            overlays[i].setAttribute("aria-hidden", "false");
                            overlays[i].focus();
                        }

                        // make sure overlay closers exist
                        if ((typeof overlay_closers === "object")) {
                            for (let i = 0; i < overlay_closers.length; i++) {
                                // mark the overlay closer as active
                                overlay_closers[i].classList.add("is-active");

                                // mark the overlay closer as unhidden
                                overlay_closers[i].setAttribute("aria-hidden", "false");
                            }
                        }
                    // if the current overlay does not match the target overlay
                    } else {
                        // mark the element as inactive
                        overlays[i].classList.remove("is-active");

                        // mark the element as hidden
                        if (overlays[i].hasAttribute("aria-hidden")) {
                            overlays[i].setAttribute("aria-hidden", "true");
                        }
                    }
                }
            }
        }
    }

    // open an overlay when the page finishes loading
    window.addEventListener("load", function () {
        load_overlay(window.location.hash.substring(1));
    }, {passive: true});

    // open an overlay when the hash changes
    window.addEventListener("hashchange", function () {
        load_overlay(window.location.hash.substring(1));
    }, {passive: true});
}

// init the function
overlay_load_init();
