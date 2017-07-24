// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

(global => {
    // no service worker for previews
    global.addEventListener("fetch", (event) => {
        if (event.request.url.match(/preview=true/)) {
            return;
        }
    });

    // try to get the variable data
    global.addEventListener("message", function (e) {
        console.log(e.data);
    }, false);

    // ensure the service worker takes over as soon as possible
    global.addEventListener("install", event => event.waitUntil(global.skipWaiting()));
    global.addEventListener("activate", event => event.waitUntil(global.clients.claim()));

    // set up caching
    global.toolbox.precache(["/", "../media/logo.svg", "../media/spritesheet.svg", "../scripts/modern.js", "../styles/modern.css"]);
    global.toolbox.router.get("../media/*", toolbox.cacheFirst);
    global.toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
    global.toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});
})(self);
