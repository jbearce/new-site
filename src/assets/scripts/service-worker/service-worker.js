// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

toolbox.precache(["/?utm_source=pwa", "../styles/modern.css"]);
toolbox.router.get("../media/*", toolbox.cacheFirst);
toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});
