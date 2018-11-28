// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const SEARCH_TOGGLE = document.querySelector("[data-toggle=mobile-search]");
const SEARCH_FORM = document.querySelector("#mobile-search");
const SEARCH_INPUT = SEARCH_FORM ? SEARCH_FORM.querySelector("input[type=search]") : false;

if (SEARCH_TOGGLE && SEARCH_FORM && SEARCH_INPUT) {
    SEARCH_TOGGLE.addEventListener("click", (e) => {
        e.preventDefault();

        SEARCH_FORM.classList.add("is-active");
        SEARCH_INPUT.focus();
    });

    SEARCH_INPUT.addEventListener("blur", () => {
        if (!SEARCH_INPUT.value) {
            SEARCH_FORM.classList.remove("is-active");
            SEARCH_TOGGLE.focus();
        }
    });
}
