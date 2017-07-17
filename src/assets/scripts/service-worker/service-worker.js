// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

toolbox.precache(["/", "../styles/modern.css"]);
toolbox.router.get("../media/*", toolbox.cacheFirst);
toolbox.router.get("/wp-content/uploads/*", toolbox.cacheFirst);
toolbox.router.get("/*", toolbox.networkFirst, {NetworkTimeoutSeconds: 5});
