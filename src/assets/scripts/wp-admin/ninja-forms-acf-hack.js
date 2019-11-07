// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

/**
 * Remove Ninja Forms modals from ACF fields
 *
 * This fixes the "Add Form" button on the primary content field.
 */
window.addEventListener("DOMContentLoaded", () => {
    const ACFS = document.querySelectorAll("#acf-hidden-wp-editor, .acf-field-wysiwyg");

    ACFS.forEach((acf) => {
        const BUTTON = acf.querySelector(".button.nf-insert-form");
        const STYLE  = acf.querySelector("#nf-insert-form-modal + style");
        const MODAL  = acf.querySelector("#nf-insert-form-modal");

        if (BUTTON) {
            BUTTON.remove();
        }

        if (STYLE) {
            STYLE.remove();
        }

        if (MODAL) {
            MODAL.remove();
        }
    });
});
