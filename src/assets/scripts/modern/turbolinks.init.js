// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import NProgress from "nprogress";
import Turbolinks from "turbolinks";

Turbolinks.start();

document.addEventListener("turbolinks:click", () => {
    NProgress.start();
});

document.addEventListener("turbolinks:render", () => {
    NProgress.done();
    NProgress.remove();
});
