<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// set a cookie after the first load to mark returning visitors
function __gulp_init__namespace_set_return_visitor_cookie() {
    if (!isset($_COOKIE["return_visitor"])) setcookie("return_visitor", "true", time() + 604800);
}
add_action("wp_enqueue_scripts", "__gulp_init__namespace_set_return_visitor_cookie", 999);

// push the CSS & JS over HTTP2
function __gulp_init__namespace_http2_push() {
    global $wp_styles;

    $http2_string = "";

    foreach ($wp_styles->queue as $style) {
        $http2_string .= ($http2_string !== "" ? ", " : "") . "<{$wp_styles->registered[$style]->src}>; rel=preload; as=style";
    }

    header("Link: {$http2_string}");
}
add_action("wp_enqueue_scripts", "__gulp_init__namespace_http2_push", 999);

// adjust WordPress login screen styles
function __gulp_init__namespace_login_styles() {
    echo "<link href='" . get_bloginfo("template_directory") . "/assets/styles/wp-login.css' rel='stylesheet' />";
    wp_enqueue_script("__gulp_init__namespace_critical_scripts", get_bloginfo("template_directory") . "/assets/scripts/critical.js", array(), false, true);
}
add_action("login_enqueue_scripts", "__gulp_init__namespace_login_styles");

// change login logo URL
function __gulp_init__namespace_login_logo_url() {
    return get_bloginfo("url");
}
add_filter("login_headerurl", "__gulp_init__namespace_login_logo_url");

// change login logo title
function __gulp_init__namespace_login_logo_title() {
    return get_bloginfo("name");
}
add_filter("login_headertitle", "__gulp_init__namespace_login_logo_title");

// add user-content class to TinyMCE body
function __gulp_init__namespace_tinymce_settings($settings) {
    $settings["body_class"] .= " user-content";
    return $settings;
}
add_filter("tiny_mce_before_init", "__gulp_init__namespace_tinymce_settings");

// fix Ninja Forms HTML field formatting
function __gulp_init__namespace_ninja_forms_html($default_value, $field_class, $field_settings) {
    if ($field_settings["type"] === "html") {
        $default_value = apply_filters("the_content", $default_value);
    }

    return $default_value;
}
add_filter("ninja_forms_render_default_value", "__gulp_init__namespace_ninja_forms_html", 10, 3);

// fix Ninja Forms not being output when no content exists and selected via meta box
function __gulp_init__namespace_fix_ninja_forms($content) {
    return !$content && get_post_meta(get_the_ID(), "ninja_forms_form", true) ? "<!-- ninja form -->" : $content;
}
add_filter("the_content", "__gulp_init__namespace_fix_ninja_forms", 5);

// fix shortcode formatting
function __gulp_init__namespace_fix_shortcodes() {
    if (!is_admin()) {
        remove_filter("the_content", "wpautop");
        remove_filter("acf_the_content", "wpautop");
        add_filter("the_content", "wpautop", 12);
        add_filter("acf_the_content", "wpautop", 12);
    }
}
add_action("loop_start", "__gulp_init__namespace_fix_shortcodes", 10);

// add classes to elements
function __gulp_init__namespace_add_user_content_classes($content) {
    global $post;

    if ($content) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$content}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $existing_classes = $anchor->getAttribute("class") ? $anchor->getAttribute("class") : "";

            if (preg_match("/button/i", $existing_classes)) {
                $anchor->setAttribute("class", "user-content_button {$existing_classes}");
            } else {
                $anchor->setAttribute("class", "user-content_link link {$existing_classes}");
            }

            $existing_rel = $anchor->getAttribute("rel");

            if (!$existing_rel) {
                $anchor->setAttribute("rel", "noopener");
            }
        }

        $h1s = $DOM->getElementsByTagName("h1");

        foreach ($h1s as $h1) {
            $h1->setAttribute("class", "user-content_title title -h1 {$h1->getAttribute("class")}");
        }

        $h2s = $DOM->getElementsByTagName("h2");

        foreach ($h2s as $h2) {
            $h2->setAttribute("class", "user-content_title title -h2 {$h2->getAttribute("class")}");
        }

        $h3s = $DOM->getElementsByTagName("h3");

        foreach ($h3s as $h3) {
            $h3->setAttribute("class", "user-content_title title -h3 {$h3->getAttribute("class")}");
        }

        $h4s = $DOM->getElementsByTagName("h4");

        foreach ($h4s as $h4) {
            $h4->setAttribute("class", "user-content_title title -h4 {$h4->getAttribute("class")}");
        }

        $h5s = $DOM->getElementsByTagName("h5");

        foreach ($h5s as $h5) {
            $h5->setAttribute("class", "user-content_title title -h5 {$h5->getAttribute("class")}");
        }

        $h6s = $DOM->getElementsByTagName("h6");

        foreach ($h6s as $h6) {
            $h6->setAttribute("class", "user-content_title title -h6 {$h6->getAttribute("class")}");
        }

        $paragraphs = $DOM->getElementsByTagName("p");

        foreach ($paragraphs as $paragraph) {
            $paragraph->setAttribute("class", "user-content_text text {$paragraph->getAttribute("class")}");
        }

        $ordered_lists = $DOM->getElementsByTagName("ol");

        foreach ($ordered_lists as $ordered_list) {
            $ordered_list->setAttribute("class", "user-content_text text -list -ordered {$ordered_list->getAttribute("class")}");
        }

        $unordered_lists = $DOM->getElementsByTagName("ul");

        foreach ($unordered_lists as $unordered_list) {
            $unordered_list->setAttribute("class", "user-content_text text -list -unordered {$unordered_list->getAttribute("class")}");
        }

        $list_items = $DOM->getElementsByTagName("li");

        foreach ($list_items as $list_item) {
            $list_item->setAttribute("class", "text_list-item {$list_item->getAttribute("class")}");
        }

        $tables = $DOM->getElementsByTagName("table");

        foreach ($tables as $table) {
            $table->setAttribute("class", "user-content_text text -table {$table->getAttribute("class")}");
        }

        $table_headers = $DOM->getElementsByTagName("thead");

        foreach ($table_headers as $table_header) {
            $table_header->setAttribute("class", "text_header {$table_header->getAttribute("class")}");
        }

        $table_bodies = $DOM->getElementsByTagName("tbody");

        foreach ($table_bodies as $tbody) {
            $tbody->setAttribute("class", "text_body {$tbody->getAttribute("class")}");
        }

        $table_footers = $DOM->getElementsByTagName("tfoot");

        foreach ($table_footers as $table_footer) {
            $table_footer->setAttribute("class", "text_footer {$table_footer->getAttribute("class")}");
        }

        $table_rows = $DOM->getElementsByTagName("tr");

        foreach ($table_rows as $table_row) {
            $table_row->setAttribute("class", "text_row {$table_row->getAttribute("class")}");
        }

        $table_cell_headers = $DOM->getElementsByTagName("th");

        foreach ($table_cell_headers as $table_cell_header) {
            $table_cell_header->setAttribute("class", "text_cell -header {$table_cell_header->getAttribute("class")}");
        }

        $table_cells = $DOM->getElementsByTagName("td");

        foreach ($table_cells as $table_cell) {
            $table_cell->setAttribute("class", "text_cell {$table_cell->getAttribute("class")}");
        }

        $blockquotes = $DOM->getElementsByTagName("blockquote");

        foreach ($blockquotes as $blockquote) {
            $blockquote->setAttribute("class", "user-content_blockquote blockquote {$blockquote->getAttribute("class")}");
        }

        $horizontal_rules = $DOM->getElementsByTagName("hr");

        foreach ($horizontal_rules as $horizontal_rule) {
            $horizontal_rule->setAttribute("class", "user-content_divider divider {$horizontal_rule->getAttribute("class")}");
        }

        $figures = $DOM->getElementsByTagName("figure");

        foreach ($figures as $figure) {
            $figure->setAttribute("class", "user-content_figure figure {$figure->getAttribute("class")}");
        }

        $figcaptions = $DOM->getElementsByTagName("figcaption");

        foreach ($figcaptions as $figcaption) {
            $figcaption->setAttribute("class", "user-content_text text {$figcaption->getAttribute("class")}");
        }

        $tables = $DOM->getElementsByTagName("table");

        $table_container = $DOM->createElement("div");
        $table_container->setAttribute("class", "user-content_text_table_container text_table_container");

        foreach ($tables as $table) {
            $table_container_clone = $table_container->cloneNode();
            $table->parentNode->replaceChild($table_container_clone, $table);
            $table_container_clone->appendChild($table);
        }

        $iframes = $DOM->getElementsByTagName("iframe");

        $iframe_container = $DOM->createElement("div");
        $iframe_container->setAttribute("class", "user-content_iframe_container iframe_container");

        foreach ($iframes as $iframe) {
            $iframe->setAttribute("class", "iframe {$iframe->getAttribute("class")}");

            $aspect_ratio = "56.25%";

            $height = $iframe->getAttribute("height");
            $width  = $iframe->getAttribute("width");

            $iframe->removeAttribute("height");
            $iframe->removeAttribute("width");

            if ($height && $width) {
                $height = (int) preg_replace("/[^0-9]/", "", $height);
                $width  = (int) preg_replace("/[^0-9]/", "", $width);

                $aspect_ratio = ($height / $width * 100) . "%";
            }

            $iframe_container_clone = $iframe_container->cloneNode();

            $iframe_container_clone->setAttribute("style", "padding-bottom:{$aspect_ratio};");

            $iframe_parent_node = $iframe->parentNode;

            if ($iframe_parent_node->tagName === "p") {
                $iframe_parent_node->parentNode->replaceChild($iframe_container_clone, $iframe_parent_node);
            } else {
                $iframe->parentNode->replaceChild($iframe_container_clone, $iframe);
            }

            $iframe_container_clone->appendChild($iframe);
        }

        // XPath required otherwise an infinite loop occurs
        $XPath = new DOMXPath($DOM);

        $images = $XPath->query("//img");

        foreach ($images as $image) {
            if ($image->parentNode->nodeName !== "noscript") {
                $existing_src    = $image->getAttribute("src");
                $existing_srcset = $image->getAttribute("srcset");

                // add noscript before images
                $noscript = $DOM->createElement("noscript");
                $noscript->appendChild($image->cloneNode());
                $image->parentNode->insertBefore($noscript, $image);

                // change src to data-normal
                if ($existing_src) {
                    $image->removeAttribute("src");
                    $image->setAttribute("data-normal", $existing_src);
                }

                // change srcset to data-srcset
                if ($existing_srcset) {
                    $image->removeAttribute("srcset");
                    $image->setAttribute("data-srcset", $existing_srcset);
                }

                // add _js class
                $image->setAttribute("class", "_js {$image->getAttribute("class")}");
            }
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_add_user_content_classes", 10);
add_filter("acf_the_content", "__gulp_init__namespace_add_user_content_classes", 10);

// remove dimensions from thumbnails
function __gulp_init__namespace_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    global $post;

    if ($html) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$html}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $DOM->getElementsByTagName("img");

        foreach ($images as $image) {
            $image->removeAttribute("height");
            $image->removeAttribute("width");
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $html = $DOM->saveHTML();
    }

    return $html;
}
add_filter("post_thumbnail_html", "__gulp_init__namespace_remove_thumbnail_dimensions", 10, 3);

// add "Download Adobe Reader" link on all pages that link to PDFs
function __gulp_init__namespace_acrobat_link() {
    global $post;

    $has_pdf = false;
    $content = get_the_content();
    $fields  = get_fields();
    $output  = "";

    if ($content) {
        preg_match("/\.pdf(?:\'|\")/im", $content, $matches);

        if ($matches) {
            $has_pdf = true;
        }
    }

    if ($fields && !$has_pdf) {
        foreach ($fields as $field) {
            preg_match("/\.pdf(?:\'|\"|$)/im", json_encode($field), $matches);

            if ($matches) {
                $has_pdf = true;
                break;
            }
        }
    }

    if ($has_pdf === true) {
        $output .= "<hr class='divider' />";
        $output .= "<p class='content_text text _small'>" . sprintf(__("Having trouble opening PDFs? %sDownload Adobe Reader here.%s", "__gulp_init__namespace"), "<a class='text_link link' href='https://get.adobe.com/reader/' target='_blank' rel='noopener'>", "</a>") . "</p>";
    }

    echo $output;
}
add_filter("__gulp_init__namespace_after_content", "__gulp_init__namespace_acrobat_link");

// disable Ninja Forms styles
function __gulp_init__namespace_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "__gulp_init__namespace_dequeue_nf_display", 999);

// redirect to the home template if no front page is set
function __gulp_init__namespace_home_template_redirect($template) {
    if (is_front_page() && get_option("show_on_front") != "page") {
        return TEMPLATEPATH . "/home.php";
    } else {
        return $template;
    }
}
add_action("template_include", "__gulp_init__namespace_home_template_redirect");

// decode HTML entities in bloginfo("description")
function __gulp_init__namespace_decode_html_entities_in_blog_description($value, $field) {
    if ($field === "description") {
        $value = html_entity_decode($value);
    }

    return $value;
}
add_filter("bloginfo", "__gulp_init__namespace_decode_html_entities_in_blog_description", 10, 2);

/* ------------------------------------------------------------------------ *\
 * Tribe Events
\* ------------------------------------------------------------------------ */

// dequue tribe calendar styles
function __gulp_init__namespace_tribe_dequeue_calendar_styles() {
    wp_dequeue_style("tribe-events-calendar-style", 999);
}
add_action("wp_enqueue_scripts", "__gulp_init__namespace_tribe_dequeue_calendar_styles");

// remove the tribe events promo
function __gulp_init__namespace_tribe_disable_promo($echo) {
    return false;
}
add_action("tribe_events_promo_banner", "__gulp_init__namespace_tribe_disable_promo");

// remove wpautop from tribe events pages
function __gulp_init__namespace_tribe_remove_content_filters() {
    if (is_tribe_page()) {
        remove_filter("the_content", "wpautop", 12);
        remove_filter("acf_the_content", "wpautop", 12);
    }
}
add_action("loop_start", "__gulp_init__namespace_tribe_remove_content_filters", 999);

// add 'menu-list_link link' to list of classes for tribe monthly pagination link
function __gulp_init__namespace_tribe_add_pagination_menu_link_class($html) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$html}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $anchors = $DOM->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        $anchor->setAttribute("class", "menu-list_link link {$anchor->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $html = $DOM->saveHTML();

    return $html;
}
add_filter("tribe_events_the_previous_month_link", "__gulp_init__namespace_tribe_add_pagination_menu_link_class");
add_filter("tribe_events_the_next_month_link", "__gulp_init__namespace_tribe_add_pagination_menu_link_class");

// add 'button' to list of classes for tribe ical buttons
function __gulp_init__namespace_tribe_ical_link_button_class($calendar_links) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$calendar_links}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $anchors = $DOM->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        $anchor->setAttribute("class", "button {$anchor->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $calendar_links = $DOM->saveHTML();

    return $calendar_links;
}
add_filter("tribe_events_ical_single_event_links", "__gulp_init__namespace_tribe_ical_link_button_class");

// add 'title -divider' class to tribe date headers
function __gulp_init__namespace_tribe_add_title_class_to_date_headers($html) {
    if ($html) {
        $html = "<h4 class='tribe-events-title title -h4 -divider'>$html</h4>";
    }

    return $html;
}
add_filter("tribe_events_list_the_date_headers", "__gulp_init__namespace_tribe_add_title_class_to_date_headers");

// add 'tribe-events-text_text text' class to tribe excerpts
function __gulp_init__namespace_tribe_add_text_class_to_excerpt($excerpt) {
    $DOM = new DOMDocument();
    $DOM->loadHTML(mb_convert_encoding("<html>{$excerpt}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $paragraphs = $DOM->getElementsByTagName("p");

    foreach ($paragraphs as $paragraph) {
        $paragraph->setAttribute("class", "tribe-events-text_text text {$paragraph->getAttribute("class")}");
    }

    // remove unneeded HTML tag
    $DOM = remove_root_tag($DOM);

    $excerpt = $DOM->saveHTML();

    return $excerpt;
}
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_tribe_add_text_class_to_excerpt");
