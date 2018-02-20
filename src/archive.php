<?php
$post_variant = isset($post_vairant) ? " {$post_variant}" : "";

if (!isset($post_type_label)) {
    $queried_object = get_queried_object();

    if (is_post_type_archive() && isset($queried_object->labels->name)) {
        $post_type_label = strtolower($queried_object->labels->name);
    } elseif (is_archive()) {
        global $wp_taxonomies;

        if (isset($queried_object->taxonomy)) {
            $post_types = isset($wp_taxonomies[$queried_object->taxonomy]) ? $wp_taxonomies[$queried_object->taxonomy]->object_type : "";

            if (isset($post_types[0])) {
                $post_type = get_post_type_object($post_types[0]);

                if (isset($post_type->label)) {
                    $post_type_label = strtolower($post_type->label);
                }
            }

            if (!isset($post_taxonomy_label)) {
                $taxonomy_labels = get_taxonomy_labels(get_taxonomy($queried_object->taxonomy));
                if (isset($taxonomy_labels->singular_name)) {
                    $post_taxonomy_label = strtolower($taxonomy_labels->singular_name);
                }
            }
        }
    }
}

if (!isset($post_type_label)) {
    $post_type_label = __("posts", "nssra");
}

if (!isset($post_taxonomy_label)) {
    $post_taxonomy_label = __("taxonomy", "nssra");
}

if (!isset($post_error)) {
    if (is_post_type_archive()) {
        $post_error = sprintf(__("Sorry, no %s could be found.", "nssra"), $post_type_label);
    } elseif (is_archive()) {
        $post_error = sprintf(__("Sorry, no %s could be found in this %s.", "nssra"), $post_type_label, $post_taxonomy_label);
    } elseif (is_search()) {
        if (get_search_query()) {
            $post_error = sprintf(__("Sorry, no %s could be found for the search phrase %s%s.%s", "nssra"), $post_type_label, "&ldquo;", get_search_query(), "&rdquo;");
        } else {
            $post_error = __("No search query was entered.", "nssra");
        }
    } else {
        $post_error = sprintf(__("Sorry, no %s could be found matching this criteria.", "nssra"), $post_type_label);
    }
}
?>

<?php if ($post_error): ?>
    <article class="article<?php echo $post_variant; ?>">
        <div class="article_content">
            <p class="article_text text"><?php echo $post_error; ?></p>
        </div><!--/.article_content-->
    </article><!--/.article-->
<?php endif; ?>

<?php
unset($post_variant);
unset($post_type_label);
unset($post_taxonomy_label);
unset($post_error);
?>
