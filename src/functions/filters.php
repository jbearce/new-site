<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// enable force HTTPS and HSTS if the site is served over HTTPS
function __gulp_init_namespace___enable_https_directives($value) {
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
        return true;
    }
}
add_action("__gulp_init_namespace___htaccess_rewrites_forcing-https_is_enabled", "__gulp_init_namespace___enable_https_directives");
add_action("__gulp_init_namespace___htaccess_security_http-strict-transport-security-hsts_is_enabled", "__gulp_init_namespace___enable_https_directives");

// disable xmlrpc.php
add_filter("xmlrpc_enabled", "__return_false");

// set a cookie after the first load to mark returning visitors
function __gulp_init_namespace___set_return_visitor_cookie() {
    if (!isset($_COOKIE["return_visitor"])) setcookie("return_visitor", "true", time() + 604800);
}
add_action("wp", "__gulp_init_namespace___set_return_visitor_cookie", 10);

// change login logo URL
function __gulp_init_namespace___login_logo_url() {
    return get_bloginfo("url");
}
add_filter("login_headerurl", "__gulp_init_namespace___login_logo_url");

// change login logo title
function __gulp_init_namespace___login_logo_title() {
    return get_bloginfo("name");
}
add_filter("login_headertext", "__gulp_init_namespace___login_logo_title");

// replace content with a password form if a post is password protected
function __gulp_init_namespace___enable_post_password_protection($post_object) {
    if (post_password_required($post_object->ID)) {
        $post_object->post_content = get_the_password_form();
    }
}
add_action("the_post", "__gulp_init_namespace___enable_post_password_protection");

// delay when shortcodes get expanded
function __gulp_init_namespace___delay_shortcode_expansion() {
    remove_filter("the_content", "do_shortcode", 11);
    add_filter("the_content", "do_shortcode", 25);
}
add_action("wp", "__gulp_init_namespace___delay_shortcode_expansion");

// filter out   and   characters on post save
function __gulp_init_namespace___remove_sep_characters($content) {
    return preg_replace("/( | )/", "", $content);
}
add_filter("content_save_pre", "__gulp_init_namespace___remove_sep_characters");

// remove wpautop stuff from shortcodes
function __gulp_init_namespace___fix_shortcodes($content) {
    global $shortcode_tags;

    if (!is_admin() && $content && $shortcode_tags) {
        $shortcodes = array();

        foreach ($shortcode_tags as $tag => $data) {
            $shortcodes[] = $tag;
        }

        $block = join("|", $shortcodes);

        $content = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content);
        $content = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $content);
    }

    return $content;
}
add_action("the_content", "__gulp_init_namespace___fix_shortcodes", 15);

// add classes to elements
function __gulp_init_namespace___add_user_content_classes($content) {
    if (!is_admin() && $content) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$content}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $existing_classes = $anchor->getAttribute("class") ? $anchor->getAttribute("class") : "";
            $existing_href    = $anchor->getAttribute("href");
            $existing_rel     = $anchor->getAttribute("rel");

            if (preg_match("/button/i", $existing_classes)) {
                $anchor->setAttribute("class", "user-content__button {$existing_classes}");
            } else {
                $anchor->setAttribute("class", "user-content__link link {$existing_classes}");
            }

            if (preg_match("/(jpg|jpeg|png|gif)$/i", $existing_href)) {
                $document_root = $_SERVER["DOCUMENT_ROOT"];

                $img_path = realpath($document_root . parse_url($existing_href, PHP_URL_PATH));

                if (file_exists($img_path)) {
                    $img_size = getimagesize($img_path);

                    if ($img_size) {
                        $anchor->setAttribute("data-size", "{$img_size[0]}x{$img_size[1]}");
                        $anchor->setAttribute("class", "photoswipe {$existing_classes}");
                    }
                }
            }

            if (!$existing_rel) {
                $anchor->setAttribute("rel", "noopener");
            }
        }

        $h1s = $DOM->getElementsByTagName("h1");

        foreach ($h1s as $h1) {
            $h1->setAttribute("class", "user-content__title title title--h1 {$h1->getAttribute("class")}");
        }

        $h2s = $DOM->getElementsByTagName("h2");

        foreach ($h2s as $h2) {
            $h2->setAttribute("class", "user-content__title title title--h2 {$h2->getAttribute("class")}");
        }

        $h3s = $DOM->getElementsByTagName("h3");

        foreach ($h3s as $h3) {
            $h3->setAttribute("class", "user-content__title title title--h3 {$h3->getAttribute("class")}");
        }

        $h4s = $DOM->getElementsByTagName("h4");

        foreach ($h4s as $h4) {
            $h4->setAttribute("class", "user-content__title title title--h4 {$h4->getAttribute("class")}");
        }

        $h5s = $DOM->getElementsByTagName("h5");

        foreach ($h5s as $h5) {
            $h5->setAttribute("class", "user-content__title title title--h5 {$h5->getAttribute("class")}");
        }

        $h6s = $DOM->getElementsByTagName("h6");

        foreach ($h6s as $h6) {
            $h6->setAttribute("class", "user-content__title title title--h6 {$h6->getAttribute("class")}");
        }

        $paragraphs = $DOM->getElementsByTagName("p");

        foreach ($paragraphs as $paragraph) {
            $paragraph->setAttribute("class", "user-content__text text {$paragraph->getAttribute("class")}");
        }

        $ordered_lists = $DOM->getElementsByTagName("ol");

        foreach ($ordered_lists as $ordered_list) {
            $ordered_list->setAttribute("class", "user-content__text text text--list text--ordered {$ordered_list->getAttribute("class")}");
        }

        $unordered_lists = $DOM->getElementsByTagName("ul");

        foreach ($unordered_lists as $unordered_list) {
            $unordered_list->setAttribute("class", "user-content__text text text--list text--unordered {$unordered_list->getAttribute("class")}");
        }

        $list_items = $DOM->getElementsByTagName("li");

        foreach ($list_items as $list_item) {
            $list_item->setAttribute("class", "text__list-item {$list_item->getAttribute("class")}");
        }

        $tables = $DOM->getElementsByTagName("table");

        foreach ($tables as $table) {
            $table->setAttribute("class", "user-content__text text text--table {$table->getAttribute("class")}");
        }

        $table_headers = $DOM->getElementsByTagName("thead");

        foreach ($table_headers as $table_header) {
            $table_header->setAttribute("class", "text__header {$table_header->getAttribute("class")}");
        }

        $table_bodies = $DOM->getElementsByTagName("tbody");

        foreach ($table_bodies as $tbody) {
            $tbody->setAttribute("class", "text__body {$tbody->getAttribute("class")}");
        }

        $table_footers = $DOM->getElementsByTagName("tfoot");

        foreach ($table_footers as $table_footer) {
            $table_footer->setAttribute("class", "text__footer {$table_footer->getAttribute("class")}");
        }

        $table_rows = $DOM->getElementsByTagName("tr");

        foreach ($table_rows as $table_row) {
            $table_row->setAttribute("class", "text__row {$table_row->getAttribute("class")}");
        }

        $table_cell_headers = $DOM->getElementsByTagName("th");

        foreach ($table_cell_headers as $table_cell_header) {
            $table_cell_header->setAttribute("class", "text__cell text__cell--header {$table_cell_header->getAttribute("class")}");
        }

        $table_cells = $DOM->getElementsByTagName("td");

        foreach ($table_cells as $table_cell) {
            $table_cell->setAttribute("class", "text__cell {$table_cell->getAttribute("class")}");
        }

        $blockquotes = $DOM->getElementsByTagName("blockquote");

        foreach ($blockquotes as $blockquote) {
            $blockquote->setAttribute("class", "user-content__blockquote blockquote {$blockquote->getAttribute("class")}");
        }

        $horizontal_rules = $DOM->getElementsByTagName("hr");

        foreach ($horizontal_rules as $horizontal_rule) {
            $horizontal_rule->setAttribute("class", "user-content__divider divider {$horizontal_rule->getAttribute("class")}");
        }

        $figures = $DOM->getElementsByTagName("figure");

        foreach ($figures as $figure) {
            $figure->setAttribute("class", "user-content__figure figure {$figure->getAttribute("class")}");
        }

        $figcaptions = $DOM->getElementsByTagName("figcaption");

        foreach ($figcaptions as $figcaption) {
            $figcaption->setAttribute("class", "user-content__text text {$figcaption->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $content = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $content;
}
add_filter("the_content", "__gulp_init_namespace___add_user_content_classes", 20);
add_action("the_content", "__gulp_init_namespace___fix_shortcodes", 15);

// enable responsive iframes
function __gulp_init_namespace___responsive_iframes($content) {
    if (!is_admin() && $content) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$content}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $iframes = $DOM->getElementsByTagName("iframe");

        $iframe_container = $DOM->createElement("div");
        $iframe_container->setAttribute("class", "user-content__iframe__container iframe__container");

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

        // remove unneeded tags (inserted for parsing reasons)
        $content = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $content;
}
add_filter("the_content", "__gulp_init_namespace___responsive_iframes", 20);

// enable responsive tables
function __gulp_init_namespace___responsive_tables($content) {
    if (!is_admin() && $content) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$content}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $tables = $DOM->getElementsByTagName("table");

        $table_container = $DOM->createElement("div");
        $table_container->setAttribute("class", "user-content__text__table__container text__table__container");
        $table_container->setAttribute("data-slideout-ignore", "true");

        foreach ($tables as $table) {
            $table_container_clone = $table_container->cloneNode();
            $table->parentNode->replaceChild($table_container_clone, $table);
            $table_container_clone->appendChild($table);
        }

        // remove unneeded tags (inserted for parsing reasons)
        $content = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $content;
}
add_filter("the_content", "__gulp_init_namespace___responsive_tables", 20);

// lazy load images
function __gulp_init_namespace___lazy_load_images($content) {
    if (!is_admin() && $content) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$content}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        // XPath required otherwise an infinite loop occurs
        $XPath = new DOMXPath($DOM);

        $images = $XPath->query("//*[self::img or self::source]");

        foreach ($images as $image) {
            if (!preg_match("/wp-caption-image/", $image->getAttribute("class")) && $image->parentNode->nodeName !== "noscript") {
                $existing_src    = $image->getAttribute("src");
                $existing_srcset = $image->getAttribute("srcset");

                // add noscript before images
                $noscript = $DOM->createElement("noscript");
                $noscript->appendChild($image->cloneNode());
                $image->parentNode->insertBefore($noscript, $image);

                // change src to data-src
                if ($existing_src) {
                    $image->removeAttribute("src");
                    $image->setAttribute("data-src", $existing_src);
                }

                // change srcset to data-srcset
                if ($existing_srcset) {
                    $image->removeAttribute("srcset");
                    $image->setAttribute("data-srcset", $existing_srcset);
                }

                $height = $image->getAttribute("height");
                $width  = $image->getAttribute("width");

                // add intrinsicsize if height and width exist
                if ($height && $width) {
                    $image->setAttribute("intrinsicsize", "{$width}x{$height}");
                }

                // add loading="lazy"
                $image->setAttribute("loading", "lazy");

                // add lazyload and __js classes
                $image->setAttribute("class", "lazyload __js {$image->getAttribute("class")}");
            }
        }

        // remove unneeded tags (inserted for parsing reasons)
        $content = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $content;
}
add_filter("the_content", "__gulp_init_namespace___lazy_load_images", 20, 1);
add_filter("post_thumbnail_html", "__gulp_init_namespace___lazy_load_images", 20, 1);
add_filter("__gulp_init_namespace___lazy_load_images", "__gulp_init_namespace___lazy_load_images", 20, 1);

// add a class to images within the caption shortcode
function __gulp_init_namespace___wp_caption_shortcode_add_image_class($shcode, $html) {
    $shcode = preg_replace("/(<img[^>]+class=(?:\"|'))/", "$1wp-caption-image ", $shcode);
    return $shcode;
}
add_filter("image_add_caption_shortcode", "__gulp_init_namespace___wp_caption_shortcode_add_image_class", 10, 2);

// remove dimensions from thumbnails
function __gulp_init_namespace___remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    if (!is_admin() && $html) {
        $DOM = new DOMDocument();

        global $post;

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$html}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $images = $DOM->getElementsByTagName("img");

        foreach ($images as $image) {
            $image->removeAttribute("height");
            $image->removeAttribute("width");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $html = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $html;
}
add_filter("post_thumbnail_html", "__gulp_init_namespace___remove_thumbnail_dimensions", 10, 3);

// add link classes to __gulp_init_namespace___menu_list_link filtered content
function __gulp_init_namespace___menu_list_link_classes($links) {
    if ($links) {
        $DOM = new DOMDocument();

        // disable errors to get around HTML5 warnings...
        libxml_use_internal_errors(true);

        // load in content
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$links}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NODEFDTD);

        // reset errors to get around HTML5 warnings...
        libxml_clear_errors();

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $anchor->setAttribute("class", "menu-list__link link {$anchor->getAttribute("class")}");
        }

        // remove unneeded tags (inserted for parsing reasons)
        $links = __gulp_init_namespace___remove_extra_tags($DOM);
    }

    return $links;
}
add_filter("__gulp_init_namespace___menu_list_link", "__gulp_init_namespace___menu_list_link_classes");

// redirect to the home template if no front page is set
function __gulp_init_namespace___home_template_redirect($template) {
    if (is_front_page() && get_option("show_on_front") != "page") {
        $template = locate_template(array("home.php", "page.php", "index.php"));
    }

    return $template;
}
add_filter("template_include", "__gulp_init_namespace___home_template_redirect");

// decode HTML entities in bloginfo("description")
function __gulp_init_namespace___decode_html_entities_in_blog_description($value, $field) {
    if ($field === "description") {
        $value = html_entity_decode($value);
    }

    return $value;
}
add_filter("bloginfo", "__gulp_init_namespace___decode_html_entities_in_blog_description", 10, 2);

// add "Download Adobe Reader" link on all pages that link to PDFs
function __gulp_init_namespace___acrobat_link() {
    global $post;

    if ($post) {
        $has_pdf = false;
        $content = get_the_content();
        $fields  = function_exists("get_fields") ? get_fields() : false;
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
            $output .= "<p class='content__text text __small'>" . sprintf(__("Having trouble opening PDFs? %sDownload Adobe Reader here.%s", "__gulp_init_namespace__"), "<a class='text_link link' href='https://get.adobe.com/reader/' target='_blank' rel='noopener'>", "</a>") . "</p>";
        }

        echo $output;
    }
}
add_filter("__gulp_init_namespace___after_content", "__gulp_init_namespace___acrobat_link");

// generate default meta description if none is set
function __gulp_init_namespace___default_wpseo_metadesc($html) {
    global $post;

    if (!$html && is_singular() && $content = wp_strip_all_tags($post->post_content)) {
        return wp_trim_words(str_replace(array("\n", "\r"), " ", $content), 20, "…");
    }

    return $html;
}
add_filter("wpseo_metadesc", "__gulp_init_namespace___default_wpseo_metadesc");
