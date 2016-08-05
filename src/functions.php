<?php
/* ------------------------------------------------------------------------ *\
 * Page Speed
\* ------------------------------------------------------------------------ */

// remove version strings
function new_site_remove_script_version($src) {
    $parts = explode("?ver", $src);
    return $parts[0];
}
add_filter("script_loader_src", "new_site_remove_script_version", 15, 1);
add_filter("style_loader_src", "new_site_remove_script_version", 15, 1);

// disable oEmbed
function speed_stop_loading_wp_embed() {
    if (!is_admin()) {
        wp_deregister_script("wp-embed");
    }
}
add_action("init", "speed_stop_loading_wp_embed");

// disable Emoji
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");

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

add_theme_support("custom-logo", array(
    "height"      => 45,
    "width"       => 200,
    "flex-height" => true,
    "flex-width"  => true,
    "header-text" => array("site-title", "site-description"),
));

add_theme_support("title-tag");

add_theme_support("automatic-feed-links");

add_theme_support("post-thumbnails");


/* ------------------------------------------------------------------------ *\
 * Menus
\* ------------------------------------------------------------------------ */

// register the menus
register_nav_menus(array(
	"primary" => "Navigation",
));

// menu walker
class new_site_walker extends Walker_Nav_Menu {
    // set up a variable to hold the parameters passed to the walker
    private $params;

    // store the paramters in an accessible way
    public function __construct($params = "") {
        $this->params = $params;
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // get the current classes
        $classes = $item->classes ? $item->classes : array();

        // add the menu-list_item class if the classes array contains menu-item
        if (in_array("menu-item", $classes))
            array_push($classes, "menu-list_item");

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes))
            array_push($classes, "is-viewed");

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes))
            array_push($classes, "is-viewed");

        // add a the -parent class if the page has children
        if (in_array("menu-item-has-children", $classes))
            array_push($classes, "-parent");

        // convert the clean_classes array in to usable string
        $class_names = " class='" . esc_attr(join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item))) . "'";

        // retrieve the URL
        $url = $item->url;

        // retrieve and sanitize the title attribute
        $attr_title = $item->attr_title ? " title='" . htmlentities($item->attr_title, ENT_QUOTES) . "'" : "";

        // retrieve the target
        $target = $item->target ? " target='{$item->target}'" : "";

        // retrieve and sanitize the rel attribute
        $xfn = $item->xfn ? " rel='" . htmlentities($item->xfn, ENT_QUOTES) . "'" : "";

        // retrieve the title
        $title = $item->title;

        // retrieve and sanitize the description
        $description = $item->description ? " <span class='menu-item_description'>" . htmlentities($item->description, ENT_QUOTES) . "</span>" : "";

        // construct the menu item
        $output .= sprintf(
            "<li%s><a class='menu-list_link' href='%s'%s%s%s>%s</a>",
            $class_names,
            $url,
            $attr_title,
            $target,
            $xfn,
            $title,
            $description
        );
    }

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        // add a toggle button if the buttons paramater is passed
        $toggle = in_array("accordion", $params) ? "<button class='menu-list_toggle'><i class='fa fa-chevron-down'></i><span class='_visuallyhidden'>" . __("Click to toggle children", "new_site") . "</span></button>" : "<button class='menu-list_toggle _visuallyhidden'>" . __("Click to toggle children", "new_site") . "</button>";

        // add a -tier class indicting the depth
        $variant = "-tier1";

        if ($depth > 0) {
            if ($depth > 1) {
                $variant = "-tier" . ($depth + 1);
            } else {
                $variant = "-tier2";
            }
        }

        // add a -accordion class if the accordion parameter is passed
        $variant .= in_array("accordion", $params) ? " -accordion" : " -overlay";

        // construct the menu list
        $output .= "{$toggle}<ul class='menu-list -vertical -child {$variant}' aria-hidden='true'>";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        // close the menu list
        $output .= "</ul>";
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // close the menu item
        $output .= "</li>";
    }
}

/* ------------------------------------------------------------------------ *\
 * Styles & Scripts
\* ------------------------------------------------------------------------ */

// enqueue styles & scripts
function new_site_enqueue_scripts() {
    wp_enqueue_script("jquery");
}
add_action("wp_enqueue_scripts", "new_site_enqueue_scripts");

/* ------------------------------------------------------------------------ *\
 * Image Sizes
\* ------------------------------------------------------------------------ */

add_image_size("hero", 700, 400, true);
add_image_size("hero_medium", 1200, 400, true);
add_image_size("hero_large", 2000, 400, true);

/* ------------------------------------------------------------------------ *\
 * Filters
\* ------------------------------------------------------------------------ */

// remove dimensions from thumbnails
function new_site_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}
add_filter("post_thumbnail_html", "new_site_remove_thumbnail_dimensions", 10, 3);

// add data attributes to tables
function new_site_responsive_tables($content) {
    // @TODO write filter :)

    return $content;
}
add_filter("the_content", "new_site_responsive_tables", 10, 2);
add_filter("acf_the_content", "new_site_responsive_tables", 10, 2);

// disable Ninja Forms styles
function new_site_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "new_site_dequeue_nf_display", 999);


/* ------------------------------------------------------------------------ *\
 * Custom Functions
\* ------------------------------------------------------------------------ */

// get a nicer excerpt based on post ID
function get_better_excerpt($id = 0, $length = 55, $more = " [...]") {
    global $post;

    $post_id = $id ? $id : $post->ID;
    $post_object = get_post($post_id);
    $excerpt = $post_object->post_excerpt ? $post_object->post_excerpt : wp_trim_words(strip_shortcodes($post_object->post_content), $length, $more);

    return $excerpt;
}

/* ------------------------------------------------------------------------ *\
 * Advanced custom Fields
\* ------------------------------------------------------------------------ */

// Start Front Page Slideshow
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5788edcaf258b',
	'title' => 'Front Page Slideshow',
	'fields' => array (
		array (
			'key' => 'field_5788edcf43a44',
			'label' => 'Slideshow',
			'name' => 'slideshow',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => '',
			'max' => '',
			'layout' => 'block',
			'button_label' => 'Add Image',
			'sub_fields' => array (
				array (
					'key' => 'field_5788edd543a45',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'array',
					'preview_size' => 'hero_medium',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'page_type',
				'operator' => '==',
				'value' => 'front_page',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
// End Front Page Slideshow















add_action( 'init', array( 'XTeam_Nav_Menu_Item_Custom_Fields', 'setup' ) );

class XTeam_Nav_Menu_Item_Custom_Fields {

	static $options = array();

	static function setup() {
		// @todo we can do some merging of provided options from WP options for from config
		self::$options['fields'] = array(
			'column' =>array(
				'name'				=> 'column',
				'label'				=> __('Start a new column here', 'new_site'),
				'container_class'	=> 'column',
				'input_type' 		=> 'checkbox'
			)
		);

		add_filter( 'wp_edit_nav_menu_walker', function () {
			return 'XTeam_Walker_Nav_Menu_Edit';
		});
		add_filter( 'xteam_nav_menu_item_additional_fields', array( __CLASS__, '_add_fields' ), 10, 5 );
		add_action( 'save_post', array( __CLASS__, '_save_post' ) );
	}

	static function get_fields_schema() {
		$schema = array();
		foreach(self::$options['fields'] as $name => $field) {
			if (empty($field['name'])) {
				$field['name'] = $name;
			}
			$schema[] = $field;
		}
		return $schema;
	}

	static function get_menu_item_postmeta_key($name) {
		return '_menu_item_' . $name;
	}

	/**
	 * Inject the
	 * @hook {action} save_post
	 */
	static function _add_fields($new_fields, $item_output, $item, $depth, $args) {

		$schema = self::get_fields_schema($item->ID);

		$new_fields = '';

		foreach($schema as $field) {

			$field['value'] = get_post_meta($item->ID, self::get_menu_item_postmeta_key($field['name']), true);
			$field['id'] = $item->ID;

			$new_fields .= '<p class="field-'.$field['name'].' description"><label for="edit-menu-item-'.$field['name'].'-'.$field['id'].'">';
			$new_fields .= '<input type="'.$field['input_type'].'" ';
			$new_fields .= 'id="edit-menu-item-'.$field['name'].'-'.$field['id'].'"';
			$new_fields .= 'class="widefat code edit-menu-item-'.$field['name'].'"';
			$new_fields .= 'name="menu-item-'.$field['name'].'['.$field['id'].']"';
			if( $field['input_type'] == 'checkbox'){

				$new_fields .= 'value="1" '. checked( $field['value'], 1, false) .'';

			}else{
				$new_fields .= 'value="'.$field['value'].'" ';
			}

			$new_fields .= ' />'.$field['label'].'</label></p>';

		}
		return $new_fields;
	}

	/**
	 * Save the newly submitted fields
	 * @hook {action} save_post
	 */
	static function _save_post($post_id) {
		if (get_post_type($post_id) !== 'nav_menu_item') {
			return;
		}
		$fields_schema = self::get_fields_schema($post_id);
		foreach($fields_schema as $field_schema) {
			$form_field_name = 'menu-item-' . $field_schema['name'];
			$key = self::get_menu_item_postmeta_key($field_schema['name']);
			$value = isset( $_POST[$form_field_name][$post_id] ) ? stripslashes($_POST[$form_field_name][$post_id]) : '';
			update_post_meta($post_id, $key, $value);
		}
	}

}

require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
class XTeam_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$item_output = '';
		parent::start_el($item_output, $item, $depth, $args);
		$new_fields = apply_filters( 'xteam_nav_menu_item_additional_fields', '', $item_output, $item, $depth, $args );
		// Inject $new_fields before: <div class="menu-item-actions description-wide submitbox">
		if ($new_fields) {
			$item_output = preg_replace('/(?=<p[^>]+class="[^"]*field-css-classes)/', $new_fields, $item_output);
		}
		$output .= $item_output;
	}
}






// hide the checkbox except on depth 1
function new_site_hide_column_checkbox_except_on_depth_1() {
    if (is_admin()) {
        $current_screen = get_current_screen();

        if ($current_screen->base === "nav-menus") {
            echo "<style>.menu-item:not(.menu-item-depth-1) .field-column {display:none;}</style>";
        }
    }
}
add_action("admin_head", "new_site_hide_column_checkbox_except_on_depth_1");
