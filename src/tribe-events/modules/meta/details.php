<?php
$time_format = get_option("time_format", Tribe__Date_Utils::TIMEFORMAT);
$time_range_separator = tribe_get_option("timeRangeSeparator", " - ");

$start_datetime = tribe_get_start_date();
$start_date = tribe_get_start_date(null, false);
$start_time = tribe_get_start_date(null, false, $time_format);
$start_ts = tribe_get_start_date(null, false, Tribe__Date_Utils::DBDATEFORMAT);

$end_datetime = tribe_get_end_date();
$end_date = tribe_get_display_end_date(null, false);
$end_time = tribe_get_end_date(null, false, $time_format);
$end_ts = tribe_get_end_date(null, false, Tribe__Date_Utils::DBDATEFORMAT);

$time_formatted = null;
if ($start_time == $end_time) {
	$time_formatted = esc_html($start_time);
} else {
	$time_formatted = esc_html($start_time . $time_range_separator . $end_time);
}

$event_id = Tribe__Main::post_id_helper();

/**
 * Returns a formatted time for a single event
 *
 * @var string Formatted time string
 * @var int Event post id
 */
$time_formatted = apply_filters("tribe_events_single_event_time_formatted", $time_formatted, $event_id);

/**
 * Returns the title of the "Time" section of event details
 *
 * @var string Time title
 * @var int Event post id
 */
$time_title = apply_filters("tribe_events_single_event_time_title", __("Time:", "new_site"), $event_id);

$cost = tribe_get_formatted_cost();
$website = tribe_get_event_website_link();
?>

<div class="article-col col -half">
	<div class="article-menu-container menu-container">
		<h3 class="article_title title -sub"><?php esc_html_e("Details", "new_site"); ?></h3>
		<ul class="article-menu-list menu-list -meta -vertical">
			<?php
			do_action("tribe_events_single_meta_details_section_start");

			// All day (multiday) events
			if (tribe_event_is_all_day() && tribe_event_is_multiday()):
				?>
	            <li class="article-menu-list_item menu-list_item">
	    			<strong class="_bold"><?php esc_html_e("Start:", "new_site"); ?></strong>
	    			<?php esc_html_e($start_date) ?>
	            </li>
	            <li class="article-menu-list_item menu-list_item">
				    <strong class="_bold"><?php esc_html_e("End:", "new_site"); ?></strong>
					<?php esc_html_e($end_date); ?>
	            </li>
			<?php
			// All day (single day) events
			elseif ( tribe_event_is_all_day() ):
				?>
				<li class="article-menu-list_item menu-list_item">
	                <strong class="_bold"><?php esc_html_e("Date:", "new_site"); ?></strong>
					<?php esc_html_e($start_date); ?>
				</li>
			<?php
			// Multiday events
			elseif ( tribe_event_is_multiday() ) :
				?>
	            <li class="article-menu-list_item menu-list_item">
	    			<strong class="_bold"><?php esc_html_e("Start:", "new_site"); ?></dt>
	    			<?php esc_html_e($start_datetime); ?>
	            </li>
	            <li class="article-menu-list_item menu-list_item">
	    			<strong class="_bold"><?php esc_html_e("End:", "new_site"); ?></strong>
	    			<?php esc_html_e($end_datetime); ?>
	            </li>
			<?php
			// Single day events
			else :
				?>
	            <li class="article-menu-list_item menu-list_item">
	    			<strong class="_bold"><?php esc_html_e("Date:", "new_site"); ?></strong>
	    			<?php esc_html_e($start_date); ?>
	            </li>
	            <li class="article-menu-list_item menu-list_item">
				    <strong class="_bold"><?php echo esc_html($time_title); ?></strong>
					<?php echo $time_formatted; ?>
				</li>
			<?php endif; ?>

			<?php
			// Event Cost
			if (!empty($cost)): ?>
				<li class="article-menu-list_item menu-list_item">
	                <strong class="_bold"><?php esc_html_e("Cost:", "new_site"); ?></strong>
				    <?php esc_html_e($cost); ?>
	            </li>
			<?php endif; ?>

	        <li class="article-menu-list_item menu-list_item">
	    		<?php
	    		echo tribe_get_event_categories(
	    			get_the_id(), array(
	    				"before"       => "",
	    				"sep"          => ", ",
	    				"after"        => "",
	    				"label"        => null, // An appropriate plural/singular label will be provided
	    				"label_before" => "<strong>",
	    				"label_after"  => "</strong>",
	    				"wrap_before"  => "",
	    				"wrap_after"   => "",
	    			)
	    		);
	    		?>
	        </li>

	        <li class="article-menu-list_item menu-list_item">
	            <strong class="_bold"><?php _e("Event Tags", "new_site"); ?></strong>
	            <?php the_tags("", ", ", ""); ?>
	        </li>

			<?php
			// Event Website
			if (!empty($website)): ?>
	            <li class="article-menu-list_item menu-list_item">
	    			<strong class="_bold"><?php esc_html_e("Website:", "new_site"); ?></strong>
	    			<?php echo $website; ?>
	            </li>
			<?php endif; ?>

			<?php do_action("tribe_events_single_meta_details_section_end"); ?>
		</dl>
	</div>
</div>
