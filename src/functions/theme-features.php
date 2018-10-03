<?php
/* ------------------------------------------------------------------------ *\
 * Theme Features
\* ------------------------------------------------------------------------ */

add_theme_support("html5", array(
    "comment-list",
    "comment-form",
    "search-form",
    "gallery",
    "caption"
));

add_theme_support("title-tag");

add_theme_support("automatic-feed-links");

add_theme_support("post-thumbnails");

add_editor_style(get_theme_file_uri(__gulp_init_namespace___get_theme_file_path("assets/styles/", "editor.*.css")));
