// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Swiper from "swiper";

// init swiper
if (document.querySelectorAll(".swiper-container.--hero .swiper-slide").length > 1) {
    new Swiper (".swiper-container.--hero", {
        autoplay: {
            delay: 5000,
        },
        loop: true,
        navigation: {
            nextEl: ".swiper-button.--next",
            prevEl: ".swiper-button.--prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        speed: 150,
    });
}
