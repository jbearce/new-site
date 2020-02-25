// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import * as expiration from "workbox-expiration";
import * as precaching from "workbox-precaching";
import * as routing from "workbox-routing";
import * as strategies from "workbox-strategies";

/**
 * Cache WordPress content
 */
routing.registerRoute(
    /^(?:(?!wp-admin|wp-content|wp-includes|wp-login)(.*)\/)?$/,
    new strategies.NetworkFirst({
        cacheName: "__gulp_init_namespace__-content-cache",
        plugins: [
            new expiration.ExpirationPlugin({
                maxAgeSeconds: 7 * 24 * 60 * 60,
            }),
        ],
    })
);

/**
 * Cache CSS files
 */
routing.registerRoute(
    /\.css$/,
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-css-cache",
    })
);

/**
 * Cache JS files
 */
routing.registerRoute(
    /\.js$/,
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-js-cache",
    })
);

/**
 * Cache image files
 */
routing.registerRoute(
    /\.(gif|jpeg|jpg|png|svg|webp)$/,
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-image-cache",
        plugins: [
            new expiration.ExpirationPlugin({
                maxAgeSeconds: 7 * 24 * 60 * 60,
            }),
        ],
    })
);

/**
 * Cache font files
 */
routing.registerRoute(
    /\.(otf|ttf|woff|woff2)$/,
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-font-cache",
        plugins: [
            new expiration.ExpirationPlugin({
                maxAgeSeconds: 7 * 24 * 60 * 60,
            }),
        ],
    })
);

/**
 * Precache "offline" page
 */
precaching.precacheAndRoute([
    {
        url: "/offline/",
        revision: __VERSION__,
    },
]);

/**
 * Return "offline" page when visiting pages offline that haven't been cached
 */
routing.setCatchHandler(() => {
    return caches.match("/offline/");
});
