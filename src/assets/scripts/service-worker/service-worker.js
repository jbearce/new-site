// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// no service worker for previews
self.addEventListener("fetch", (event) => {
    if (event.request.url.match(/preview=true/)) {
        return;
    }
});

// set up caching
toolbox.precache(["/", "../media/logo.svg", "../media/spritesheet.svg", "../scripts/modern.js", "../styles/modern.css"]);
toolbox.router.get("../media/*", toolbox.cacheFirst);
toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});

// show offline error
self.toolbox.router.get("/(.*)", function (req, vals, opts) {
    return toolbox.networkFirst(req, vals, opts).catch(function (error) {
        if (req.method === "GET" && req.headers.get("accept").includes("text/html")) {
            return toolbox.cacheOnly(new Request("/offline"), vals, opts);
        }
        throw error;
    });
});
