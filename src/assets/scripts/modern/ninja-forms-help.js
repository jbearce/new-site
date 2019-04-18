// JavaScript Document

// Scripts written by Jacob Bearce @ Weblinx, Inc.

let i = 0;

const LIMIT = 10;

(function CHECK_FOR_HELP_SYMBOLS() { i++;
    const HELP_SYMBOLS = document.querySelectorAll(".ninja-forms-help-symbol");

    // check if symbols exist
    if (HELP_SYMBOLS.length > 0) {
        // listen for clicks on symbols to display tooltips
        HELP_SYMBOLS.forEach((help_symbol) => {
            help_symbol.addEventListener("click", () => {
                const TOOLTIP = help_symbol.querySelector(".ninja-forms-help-tooltip");

                help_symbol.classList.toggle("is-active");
                TOOLTIP.setAttribute("aria-hidden", (TOOLTIP.getAttribute("aria-hidden") === "true" ? "false" : "true"));
            });
        });

        // stop the loop
        return;
    }

    // stop the loop after a few tries, as a form likely isn't loading
    if (i >= LIMIT) {
        return;
    }

    // check every 100 ms if any help symbols exist
    setTimeout(CHECK_FOR_HELP_SYMBOLS, 100);
})();
