<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Post Types
\* ------------------------------------------------------------------------ *//*removeIf(resources_css_js_php)*/

// register resource post type
function α__init_namespace_create_resource_post_type() {
    $type_plural_name   = __("Resources", "α__init_namespace");
    $type_singular_name = __("Resource", "α__init_namespace");

    register_post_type("resource", array(
        "has_archive"          => true,
        "hierarchical"          => false,
        "labels"              => array(
            "add_new"              => __("Add New", "α__init_namespace"),
            "add_new_item"          => sprintf(__("Add New %s", "α__init_namespace"), $type_singular_name),
            "all_items"          => sprintf(__("All %s", "α__init_namespace"), $type_plural_name),
            "edit_item"          => sprintf(__("Edit %s", "α__init_namespace"), $type_singular_name),
            "menu_name"          => sprintf(__("%s", "nssra"), $type_plural_name),
            "name"                  => sprintf(__("%s", "nssra"), $type_plural_name),
            "new_item"              => sprintf(__("New %s", "α__init_namespace"), $type_singular_name),
            "not_found"          => sprintf(__("No %s found", "α__init_namespace"), strtolower($type_plural_name)),
            "not_found_in_trash" => sprintf(__("No %s found in Trash", "α__init_namespace"), strtolower($type_plural_name)),
            "search_items"          => sprintf(__("Search resources", "α__init_namespace")),
            "singular_name"      => sprintf(__("%s", "nssra"), $type_singular_name),
            "view_item"          => sprintf(__("View %s", "α__init_namespace"), $type_singular_name),
        ),
        "menu_icon"          => "dashicons-admin-links",
        "menu_position"      => 20,
        "public"              => true,
        "rewrite"            => array(
            "slug" => "resources",
        ),
        "show_ui"            => true,
        "supports"              => array(
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

    $taxonomy_plural_name   = __("Tags", "α__init_namespace");
    $taxonomy_singular_name = __("Tag", "α__init_namespace");

    register_taxonomy(
        "resource_tag",
        "resource",
        array(
            "capabilities"     => array("edit_terms" => "manage_categories"),
            "hierarchical"     => false,
            "labels"         => array(
                "name"                       => sprintf(_x("%s %s", "taxonomy general name", "α__init_namespace"), $type_singular_name, $taxonomy_plural_name),
                "singular_name"              => sprintf(_x("%s", "taxonomy singular name", "α__init_namespace"), $taxonomy_singular_name),
                "search_items"               => sprintf(__("Search %s", "α__init_namespace"), $taxonomy_plural_name),
                "popular_items"              => sprintf(__("Popular %s", "α__init_namespace"), $taxonomy_plural_name),
                "all_items"                  => sprintf(__("All %s", "α__init_namespace"), $taxonomy_plural_name),
                "edit_item"                  => sprintf(__("Edit %s", "α__init_namespace"), $taxonomy_singular_name),
                "update_item"                => sprintf(__("Update %s", "α__init_namespace"), $taxonomy_singular_name),
                "add_new_item"               => sprintf(__("Add New %s", "α__init_namespace"), $taxonomy_singular_name),
                "new_item_name"              => sprintf(__("New %s Name", "α__init_namespace"), $taxonomy_singular_name),
                "separate_items_with_commas" => sprintf(__("Separate %s with commas", "α__init_namespace"), strtolower($taxonomy_plural_name)),
                "add_or_remove_items"        => sprintf(__("Add or remove %s", "α__init_namespace"), strtolower($taxonomy_plural_name)),
                "choose_from_most_used"      => sprintf(__("Choose from the most used %s", "α__init_namespace"), strtolower($taxonomy_plural_name)),
                "not_found"                  => sprintf(__("No %s found.", "α__init_namespace"), strtolower($taxonomy_plural_name)),
                "menu_name"                  => sprintf(__("%s", "α__init_namespace"), $taxonomy_plural_name),
            ),
            "rewrite"         => array(
                "slug" => "resource-tag",
            ),
        )
    );
}
add_action("init", "α__init_namespace_create_resource_post_type");
/*endRemoveIf(resources_css_js_php)*/
