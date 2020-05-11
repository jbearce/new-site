// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import handorgel from "handorgel";

const HANDORGELS = document.querySelectorAll(".handorgel");

HANDORGELS.forEach((element) => {
    new handorgel(element);
});
