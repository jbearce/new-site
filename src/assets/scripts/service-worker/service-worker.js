// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

((global) => {
    // disable the service worker for post previews
    global.addEventListener("fetch", (event) => {
        if (event.request.url.match(/preview=true/)) {
            return;
        }
    });

    // ensure the service worker takes over as soon as possible
    global.addEventListener("install", event => event.waitUntil(global.skipWaiting()));
    global.addEventListener("activate", event => event.waitUntil(global.clients.claim()));

    // set up the cache
    global.toolbox.precache(["/"]);
    global.toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
    global.toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});
})(self);
