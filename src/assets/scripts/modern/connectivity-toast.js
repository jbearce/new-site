// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

import Noty from "noty";

let is_offline = false;

const OFFLINE_NOTY = new Noty({
    text: l10n.noty.offline.text,
    theme: false,
});

const UPDATE_STATUS = () => {
    if (typeof navigator.onLine !== "undefined") {
        is_offline = !navigator.onLine;

        if (is_offline) {
            OFFLINE_NOTY.show();
        } else {
            OFFLINE_NOTY.close();
        }
    }
};

const CHECK_CONNECTIVITY = () => {
    UPDATE_STATUS();
    window.addEventListener("online", UPDATE_STATUS);
    window.addEventListener("offline", UPDATE_STATUS);
};

window.addEventListener("load", CHECK_CONNECTIVITY);
