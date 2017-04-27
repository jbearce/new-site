<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Advanced custom Fields
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

// Start Front Page Callouts
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_57ab2d68b4101',
	'title' => 'Front Page Callouts',
	'fields' => array (
		array (
			'key' => 'field_57ab2d6cb53bc',
			'label' => 'Callouts',
			'name' => 'callouts',
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
			'button_label' => 'Add Callout',
			'sub_fields' => array (
				array (
					'key' => 'field_57ab2d85b53bd',
					'label' => 'Title',
					'name' => 'title',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'readonly' => 0,
					'disabled' => 0,
				),
				array (
					'key' => 'field_57ab2d8bb53be',
					'label' => 'Content',
					'name' => 'content',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
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
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
// End Front Page Callouts

// Stat Modals
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_57b5c86c01a1a',
	'title' => 'Modals',
	'fields' => array (
		array (
			'key' => 'field_57b5c87b24a5f',
			'label' => 'Modals',
			'name' => 'modals',
			'type' => 'repeater',
			'instructions' => 'To link to a modal, create a link with the URL set to #, then switch to code view, then add <code>data-overlay="modal[modal-number]"</code> to the link\'s opening tag. For example, <code>&lt;a href="#" data-overlay="modal1"&gt;</code>',
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
			'button_label' => 'Add Modal',
			'sub_fields' => array (
				array (
					'key' => 'field_57b5c88224a60',
					'label' => 'Content',
					'name' => 'content',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
			array (
				'param' => 'page_type',
				'operator' => '!=',
				'value' => 'posts_page',
			),
		),
	),
	'menu_order' => 999,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
// End Modals

// Start Options
if( function_exists('acf_add_options_page') ):

acf_add_options_page();

endif;
// End Options
