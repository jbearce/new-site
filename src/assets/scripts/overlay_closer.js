// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

var overlays = document.querySelectorAll("[data-overlay]"),
    overlay_closer = document.querySelector(".overlay-closer");

// listen for clicks on the overlay closer
overlay_closer.addEventListener("click", function (e) {
    e.preventDefault();
    close_overlay_closer();
});

// listen for esc key press
document.addEventListener("keyup", function(e) {
    if (e.keyCode === 27)
        close_overlay_closer();
});

// function to close the overlay closer
function close_overlay_closer() {
    // make sure the overlay closer exists
    if ((typeof overlay_closer === "object")) {
        overlay_closer.classList.remove("is-active");
        overlay_closer.setAttribute("aria-hidden", "true");

        // make sure overlays exist
        if ((typeof overlays === "object")) {
            for (var i = 0; i < overlays.length; i++) {
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
