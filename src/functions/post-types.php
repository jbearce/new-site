<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Post Types
\* ------------------------------------------------------------------------ */

// register project post type
function new_site_create_resource_post_type() {
    register_post_type("resource", array(
        "has_archive" 	     => true,
        "hierarchical" 	     => false,
        "labels" 		     => array(
            "add_new" 			 => __("Add New", "new_site"),
            "add_new_item" 		 => __("Add New Resource", "new_site"),
            "all_items" 		 => __("All Resources", "new_site"),
            "edit_item" 		 => __("Edit Resource", "new_site"),
            "menu_name" 		 => __("Resources", "new_site"),
            "name" 				 => __("Resources", "new_site"),
            "new_item" 			 => __("New", "new_site"),
            "not_found" 		 => __("No resources found", "new_site"),
            "not_found_in_trash" => __("No resources found in Trash", "new_site"),
            "search_items" 		 => __("Search resources", "new_site"),
            "singular_name" 	 => __("Resource", "new_site"),
            "view_item" 		 => __("View Resource", "new_site"),
	    ),
		"menu_icon" 	     => "dashicons-admin-links",
		"menu_position"      => 20,
        "public" 		     => false,
        "publicly_queryable" => true,
        "rewrite"            => array(
            "slug" => "resources",
        ),
        "show_ui"            => true,
        "supports" 		     => array(
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

    register_taxonomy(
		"resource_tag",
		"resource",
		array(
			"capabilities" 	=> array("edit_terms" => "manage_categories"),
			"hierarchical" 	=> false,
			"labels" 		=> array(
                "name"                       => _x("Resource Tags", "taxonomy general name", "new_site"),
        		"singular_name"              => _x("Tag", "taxonomy singular name", "new_site"),
        		"search_items"               => __("Search Tags", "new_site"),
        		"popular_items"              => __("Popular Tags", "new_site"),
        		"all_items"                  => __("All Tags", "new_site"),
        		"edit_item"                  => __("Edit Tag", "new_site"),
        		"update_item"                => __("Update Tag", "new_site"),
        		"add_new_item"               => __("Add New Tag", "new_site"),
        		"new_item_name"              => __("New Tag Name", "new_site"),
        		"separate_items_with_commas" => __("Separate tags with commas", "new_site"),
        		"add_or_remove_items"        => __("Add or remove tags", "new_site"),
        		"choose_from_most_used"      => __("Choose from the most used tags", "new_site"),
        		"not_found"                  => __("No tags found.", "new_site"),
        		"menu_name"                  => __("Tags", "new_site"),
			),
			"rewrite" 		=> array(
                "slug" => "resource-tag",
            ),
		)
	);
}
add_action("init", "new_site_create_resource_post_type");
