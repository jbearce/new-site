// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

function overlay_closer_init() {
    const overlays       = document.querySelectorAll("[data-overlay]");
    const overlay_closer = document.querySelector(".overlay-closer");

    // function to close the overlay closer
    function mark_overlay_closer_inactive() {
        // make sure the overlay closer exists
        if ((typeof overlay_closer === "object")) {
            overlay_closer.classList.remove("is-active");
            overlay_closer.setAttribute("aria-hidden", "true");

            // make sure overlays exist
            if ((typeof overlays === "object")) {
                for (let i = 0; i < overlays.length; i++) {
                    if (overlays[i].hasAttribute("aria-hidden")) {
                        // mark the overlay as hidden
                        overlays[i].setAttribute("aria-hidden", "true");
                    } else {
                        // focus the overlay
                        overlays[i].focus();
                    }

                    // mark the overlay as inactive
                    overlays[i].classList.remove("is-active");
                }
            }
        }
    }

    // listen for clicks on the overlay closer
    overlay_closer.addEventListener("click", function (e) {
        e.preventDefault();
        mark_overlay_closer_inactive();
    });

    // listen for esc key press
    document.addEventListener("keyup", function (e) {
        if (e.keyCode === 27) {
            mark_overlay_closer_inactive();
        }
    });
}

// init the function
overlay_closer_init();
