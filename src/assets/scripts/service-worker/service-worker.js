// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// no service worker for previews
self.addEventListener("fetch", (event) => {
    if (event.request.url.match(/preview=true/)) {
        return;
    }
});

// set up caching
toolbox.precache(["/", "./assets/styles/modern.css"]);
toolbox.router.get("./assets/media/*", toolbox.cacheFirst);
toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});
