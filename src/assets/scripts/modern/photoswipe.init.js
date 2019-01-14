// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import PhotoSwipe from "photoswipe";
import PhotoSwipeUI_Default from "photoswipe/dist/photoswipe-ui-default.js";

const OPEN_PHOTOSWIPE = (items, index = 0, msrc = false) => {
    const GALLERY = new PhotoSwipe(document.querySelector(".pswp"), PhotoSwipeUI_Default, items, {
        index,
        msrc,
        showHideOpacity: true,
    });

    GALLERY.init();
};

const PSWP_LINKS = document.querySelectorAll("a.photoswipe");

if (PSWP_LINKS.length > 0) {
    const PSWP_ITEMS = [];

    for (let i = 0; i < PSWP_LINKS.length; i++) {
        const SRC = PSWP_LINKS[i].href;
        const SIZE = PSWP_LINKS[i].dataset.size.split("x");

        PSWP_ITEMS.push({
            src: SRC,
            w: SIZE[0],
            h: SIZE[1],
        });

        PSWP_LINKS[i].addEventListener("click", (e) => {
            e.preventDefault();

            const CHILD_IMAGE = PSWP_LINKS[i].querySelector("img");
            const CHILD_IMAGE_SRC = CHILD_IMAGE ? CHILD_IMAGE.currentSrc : false;

            OPEN_PHOTOSWIPE(PSWP_ITEMS, i, CHILD_IMAGE_SRC);
        });
    }
}
