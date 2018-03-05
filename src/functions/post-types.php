<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Post Types
\* ------------------------------------------------------------------------ */
/*removeIf(resources_css_js_php)*/
// register resource post type
function new_site_create_resource_post_type() {
    $type_plural_name   = __("Resources", "new_site");
    $type_singular_name = __("Resource", "new_site");

    register_post_type("resource", array(
        "has_archive"          => true,
        "hierarchical"          => false,
        "labels"              => array(
            "add_new"              => __("Add New", "new_site"),
            "add_new_item"          => sprintf(__("Add New %s", "new_site"), $type_singular_name),
            "all_items"          => sprintf(__("All %s", "new_site"), $type_plural_name),
            "edit_item"          => sprintf(__("Edit %s", "new_site"), $type_singular_name),
            "menu_name"          => sprintf(__("%s", "nssra"), $type_plural_name),
            "name"                  => sprintf(__("%s", "nssra"), $type_plural_name),
            "new_item"              => sprintf(__("New %s", "new_site"), $type_singular_name),
            "not_found"          => sprintf(__("No %s found", "new_site"), strtolower($type_plural_name)),
            "not_found_in_trash" => sprintf(__("No %s found in Trash", "new_site"), strtolower($type_plural_name)),
            "search_items"          => sprintf(__("Search resources", "new_site")),
            "singular_name"      => sprintf(__("%s", "nssra"), $type_singular_name),
            "view_item"          => sprintf(__("View %s", "new_site"), $type_singular_name),
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

    $taxonomy_plural_name   = __("Tags", "new_site");
    $taxonomy_singular_name = __("Tag", "new_site");

    register_taxonomy(
        "resource_tag",
        "resource",
        array(
            "capabilities"     => array("edit_terms" => "manage_categories"),
            "hierarchical"     => false,
            "labels"         => array(
                "name"                       => sprintf(_x("%s %s", "taxonomy general name", "new_site"), $type_singular_name, $taxonomy_plural_name),
                "singular_name"              => sprintf(_x("%s", "taxonomy singular name", "new_site"), $taxonomy_singular_name),
                "search_items"               => sprintf(__("Search %s", "new_site"), $taxonomy_plural_name),
                "popular_items"              => sprintf(__("Popular %s", "new_site"), $taxonomy_plural_name),
                "all_items"                  => sprintf(__("All %s", "new_site"), $taxonomy_plural_name),
                "edit_item"                  => sprintf(__("Edit %s", "new_site"), $taxonomy_singular_name),
                "update_item"                => sprintf(__("Update %s", "new_site"), $taxonomy_singular_name),
                "add_new_item"               => sprintf(__("Add New %s", "new_site"), $taxonomy_singular_name),
                "new_item_name"              => sprintf(__("New %s Name", "new_site"), $taxonomy_singular_name),
                "separate_items_with_commas" => sprintf(__("Separate %s with commas", "new_site"), strtolower($taxonomy_plural_name)),
                "add_or_remove_items"        => sprintf(__("Add or remove %s", "new_site"), strtolower($taxonomy_plural_name)),
                "choose_from_most_used"      => sprintf(__("Choose from the most used %s", "new_site"), strtolower($taxonomy_plural_name)),
                "not_found"                  => sprintf(__("No %s found.", "new_site"), strtolower($taxonomy_plural_name)),
                "menu_name"                  => sprintf(__("%s", "new_site"), $taxonomy_plural_name),
            ),
            "rewrite"         => array(
                "slug" => "resource-tag",
            ),
        )
    );
}
add_action("init", "new_site_create_resource_post_type");/*endRemoveIf(resources_css_js_php)*/
