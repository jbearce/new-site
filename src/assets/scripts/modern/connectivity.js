// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import Noty from "noty";

let ifOffline = false;

const OFFLINE_NOTY = new Noty({
    callbacks: {
        afterClose: () => {
            // store a cookie so the user isn't nagged on every page load
            document.cookie = `__gulp_init_namespace___offline_noty_dismissed=true; max-age=${(60 * 60 * 6)}`;
        },
    },
    text:  l10n.noty.offline.text,
    theme: false,
});

const updateStatus = () => {
    if (typeof navigator.onLine !== "undefined") {
        ifOffline = !navigator.onLine;

        document.documentElement.classList.toggle("is-offline", ifOffline);

        if (ifOffline) {
            // check a cookie so the user isn't nagged on every page load
            if (!document.cookie.match(/__gulp_init_namespace___offline_noty_dismissed=true/)) {
                OFFLINE_NOTY.show();
            }
        } else {
            OFFLINE_NOTY.close();

            // set the cookie to false so that prompt will appear again next time they go offline
            document.cookie = `__gulp_init_namespace___offline_noty_dismissed=false; max-age=${(60 * 60 * 6)}`;
        }
    }
};

const CHECK_CONNECTIVITY = () => {
    updateStatus();
    window.addEventListener("online", updateStatus);
    window.addEventListener("offline", updateStatus);
};

window.addEventListener("load", CHECK_CONNECTIVITY);
