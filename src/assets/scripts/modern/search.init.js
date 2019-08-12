// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import "mdn-polyfills/NodeList.prototype.forEach";

const SEARCH_TOGGLE   = document.querySelector("[data-toggle=mobile-search]");
const SEARCH_FORM     = document.querySelector("#mobile-search");
const SEARCH_INPUT    = SEARCH_FORM ? SEARCH_FORM.querySelector("input[type=search]") : false;
const SEARCH_ELEMENTS = SEARCH_FORM ? SEARCH_FORM.querySelectorAll("*") : false;

if (SEARCH_TOGGLE && SEARCH_FORM && SEARCH_INPUT && SEARCH_ELEMENTS) {
    SEARCH_TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();

        SEARCH_FORM.classList.add("is-active");
        SEARCH_INPUT.focus();
    });

    SEARCH_ELEMENTS.forEach((element) => {
        element.addEventListener("blur", () => {
            setTimeout(() => {
                if (!SEARCH_FORM.contains(document.activeElement)) {
                    SEARCH_FORM.classList.remove("is-active");
                    SEARCH_TOGGLE.focus();
                }
            }, 100);
        }, { passive: true });
    });
}
