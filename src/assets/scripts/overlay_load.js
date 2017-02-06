// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// open an overlay when the page finishes loading
window.onload = function() {
    overlay_loader(window.location.hash.substring(1));
};

// open an overlay when the hash changes
window.onhashchange = function() {
    overlay_loader(window.location.hash.substring(1));
};

// function to open an overlay
function overlay_loader(target_overlay) {
    if ((typeof target_overlay !== "undefined")) {
        var overlays = document.querySelectorAll("[data-overlay]"),
            overlay_closers = document.querySelectorAll(".overlay-closer");

        if (overlays.length > 0) {
            for (var i = 0; i < overlays.length; i++) {
                // check if the current overlay matches the target overlay
                if (overlays[i].dataset.overlay === target_overlay) {
                    // mark the element as active
                    overlays[i].classList.add("is-active");

                    // mark the hidden element as unhidden, and focusi t
                    if (overlays[i].hasAttribute("aria-hidden")) {
                        overlays[i].setAttribute("aria-hidden", "false");
                        overlays[i].focus();
                    }

                    // show the overlay closer
                    for (i2 = 0; i2 < overlay_closers.length; i2++) {
                        overlay_closers[i2].classList.add("is-active");
                        overlay_closers[i2].setAttribute("aria-hidden", "false");
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
