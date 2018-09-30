<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Post Types
\* ------------------------------------------------------------------------ *//*removeIf(resources_css_js_php)*/

// register resource post type
function __gulp_init_namespace___create_resource_post_type() {
    $type_plural_name   = __("Resources", "__gulp_init_namespace__");
    $type_singular_name = __("Resource", "__gulp_init_namespace__");

    register_post_type("resource", array(
        "has_archive"   => true,
        "hierarchical"  => false,
        "labels"              => array(
            "add_new"            => __("Add New", "__gulp_init_namespace__"),
            "add_new_item"       => sprintf(__("Add New %s", "__gulp_init_namespace__"), $type_singular_name),
            "all_items"          => sprintf(__("All %s", "__gulp_init_namespace__"), $type_plural_name),
            "edit_item"          => sprintf(__("Edit %s", "__gulp_init_namespace__"), $type_singular_name),
            "menu_name"          => sprintf(__("%s", "nssra"), $type_plural_name),
            "name"               => sprintf(__("%s", "nssra"), $type_plural_name),
            "new_item"           => sprintf(__("New %s", "__gulp_init_namespace__"), $type_singular_name),
            "not_found"          => sprintf(__("No %s found", "__gulp_init_namespace__"), strtolower($type_plural_name)),
            "not_found_in_trash" => sprintf(__("No %s found in Trash", "__gulp_init_namespace__"), strtolower($type_plural_name)),
            "search_items"       => sprintf(__("Search resources", "__gulp_init_namespace__")),
            "singular_name"      => sprintf(__("%s", "nssra"), $type_singular_name),
            "view_item"          => sprintf(__("View %s", "__gulp_init_namespace__"), $type_singular_name),
        ),
        "menu_icon"     => "dashicons-admin-links",
        "menu_position" => 20,
        "public"        => true,
        "rewrite"       => array(
            "slug" => "resources",
        ),
        "show_ui"       => true,
        "supports"      => array(
            "title",
            "editor",
            "author",
            "thumbnail",
            "excerpt",
            "trackbacks",
            "custom-fields",
            "comments",
            "revisions",
            "post-formats",
        ),
    ));

    $taxonomy_plural_name   = __("Tags", "__gulp_init_namespace__");
    $taxonomy_singular_name = __("Tag", "__gulp_init_namespace__");

    register_taxonomy(
        "resource_tag",
        "resource",
        array(
            "capabilities" => array("edit_terms" => "manage_categories"),
            "hierarchical" => false,
            "labels"       => array(
                "name"                       => sprintf(_x("%s %s", "taxonomy general name", "__gulp_init_namespace__"), $type_singular_name, $taxonomy_plural_name),
                "singular_name"              => sprintf(_x("%s", "taxonomy singular name", "__gulp_init_namespace__"), $taxonomy_singular_name),
                "search_items"               => sprintf(__("Search %s", "__gulp_init_namespace__"), $taxonomy_plural_name),
                "popular_items"              => sprintf(__("Popular %s", "__gulp_init_namespace__"), $taxonomy_plural_name),
                "all_items"                  => sprintf(__("All %s", "__gulp_init_namespace__"), $taxonomy_plural_name),
                "edit_item"                  => sprintf(__("Edit %s", "__gulp_init_namespace__"), $taxonomy_singular_name),
                "update_item"                => sprintf(__("Update %s", "__gulp_init_namespace__"), $taxonomy_singular_name),
                "add_new_item"               => sprintf(__("Add New %s", "__gulp_init_namespace__"), $taxonomy_singular_name),
                "new_item_name"              => sprintf(__("New %s Name", "__gulp_init_namespace__"), $taxonomy_singular_name),
                "separate_items_with_commas" => sprintf(__("Separate %s with commas", "__gulp_init_namespace__"), strtolower($taxonomy_plural_name)),
                "add_or_remove_items"        => sprintf(__("Add or remove %s", "__gulp_init_namespace__"), strtolower($taxonomy_plural_name)),
                "choose_from_most_used"      => sprintf(__("Choose from the most used %s", "__gulp_init_namespace__"), strtolower($taxonomy_plural_name)),
                "not_found"                  => sprintf(__("No %s found.", "__gulp_init_namespace__"), strtolower($taxonomy_plural_name)),
                "menu_name"                  => sprintf(__("%s", "__gulp_init_namespace__"), $taxonomy_plural_name),
            ),
            "rewrite"      => array(
                "slug" => "resource-tag",
            ),
        )
    );
}
add_action("init", "__gulp_init_namespace___create_resource_post_type");
/*endRemoveIf(resources_css_js_php)*/
