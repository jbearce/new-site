<?php
$organizer_ids = tribe_get_organizer_ids();
$multiple = count( $organizer_ids ) > 1;

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
$website = tribe_get_organizer_website_link();
?>

<div class="article-col col -half">
	<div class="article_menu-container menu-container">
		<h3 class="article_title title -sub"><?php echo tribe_get_organizer_label(!$multiple); ?></h3>
		<ul class="article_menu-list menu-list -meta -vertical">
			<?php
			do_action("tribe_events_single_meta_organizer_section_start");

			foreach ($organizer_ids as $organizer) {
				if (!$organizer) {
					continue;
				}

				?>
				<li class="article_menu-list_item menu-list_item">
					<?php echo tribe_get_organizer_link($organizer); ?>
				</li>
				<?php
			}

			if (!$multiple) { // only show organizer details if there is one
				if (!empty($phone)) {
					?>
	                <li class="article_menu-list_item menu-list_item">
	    				<strong class="_bold"><?php esc_html_e("Phone:", "new-site"); ?></strong>
	    				<?php echo esc_html($phone); ?>
	                </li>
					<?php
				}//end if

				if (!empty($email)) {
					?>
	                <li class="article_menu-list_item menu-list_item">
	    				<strong class="_bold"><?php esc_html_e("Email:", "new-site"); ?></strong>
	    				<?php echo esc_html($email); ?>
	                </li>
					<?php
				}//end if

				if (!empty($website)) {
					?>
	                <li class="article_menu-list_item menu-list_item">
	    				<strong class="_bold"><?php esc_html_e("Website:", "new-site") ?></strong>
	    				<?php echo $website; ?>
	                </li>
					<?php
				}//end if
			}//end if

			do_action("tribe_events_single_meta_organizer_section_end");
			?>
		</ul>
	</div>
</div>
