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
    ({ url }) => {
        return (url.pathname && !url.pathname.match(/^\/(wp-admin|wp-content|wp-includes|wp-json|wp-login)/) && url.pathname.match(/\/$/));
    },
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
    ({ url }) => {
        return (url.pathname && url.pathname.match(/\.css$/) && !url.pathname.match(/wp-admin|wp-includes|wp-json/));
    },
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-css-cache",
        plugins: [
            new expiration.ExpirationPlugin({
                maxAgeSeconds: 7 * 24 * 60 * 60,
            }),
        ],
    })
);

/**
 * Cache JS files
 */
routing.registerRoute(
    ({ url }) => {
        return (url.pathname && url.pathname.match(/\.js$/) && !url.pathname.match(/wp-admin|wp-includes|wp-json/) && !url.pathname.match(/redirection/));
    },
    new strategies.CacheFirst({
        cacheName: "__gulp_init_namespace__-js-cache",
        plugins: [
            new expiration.ExpirationPlugin({
                maxAgeSeconds: 7 * 24 * 60 * 60,
            }),
        ],
    })
);

/**
 * Cache image files
 */
routing.registerRoute(
    ({ url }) => {
        return (url.pathname && url.pathname.match(/\.gif|jpeg|jpg|png|svg|webp$/) && !url.pathname.match(/wp-admin|wp-includes|wp-json/));
    },
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
    ({ url }) => {
        return (url.pathname && url.pathname.match(/\.otf|ttf|woff|woff2$/) && !url.pathname.match(/wp-admin|wp-includes|wp-json/));
    },
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
