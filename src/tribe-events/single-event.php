<?php if (!defined("ABSPATH")) {die("-1");} ?>
<?php $event_id = get_the_ID(); ?>
<div id="tribe-events-content" class="tribe-events-single">
	<p class="tribe-events-back"><a href="<?php echo tribe_get_events_link(); ?>"> <?php _e("&laquo; All Events", "tribe-events-calendar"); ?></a></p>
	<?php tribe_events_the_notices() ?>
	<?php the_title("<h2 class='tribe-events-single-event-title summary'>", "</h2>"); ?>
	<div class="tribe-events-schedule updated published tribe-clearfix">
		<h3>
			<?php echo tribe_events_event_schedule_details($event_id); ?>
			<?php if (tribe_get_cost()):  ?>
				<span class="tribe-events-divider">|</span>
				<span class="tribe-events-cost"><?php echo tribe_get_cost(null, true); ?></span>
			<?php endif; ?>
		</h3>
	</div><!--/.tribe-events-schedule.updated.published.tribe-clearfix-->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes(); ?>>
		<h3 class="tribe-events-visuallyhidden"><?php _e("Event Navigation", "tribe-events-calendar"); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link("<span>&laquo;</span> %title%") ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link("%title% <span>&raquo;</span>") ?></li>
		</ul><!--/.tribe-events-sub-nav-->
	</div><!--/#tribe-events-header-->
	<?php while (have_posts()):  the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class("vevent"); ?>>
		<?php echo tribe_event_featured_image(); ?>
		<?php do_action("tribe_events_single_event_before_the_content") ?>
		<div class="tribe-events-single-event-description tribe-events-content entry-content description">
			<?php the_content(); ?>
		</div><!--/.tribe-events-single-event-description.tribe-events-content.entry-content.description-->
		<?php do_action("tribe_events_single_event_after_the_content"); ?>
		<div class="tribe-events-meta-wrapper tribe-clearfix">
			<?php do_action("tribe_events_single_event_before_the_meta"); ?>
			<?php // echo tribe_events_single_event_meta() ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-details">
				<h3>Details</h3>
				<?php if (tribe_get_start_date(null, false) != tribe_get_end_date(null, false)): ?>
				<h4>Start:</h4>
				<p><?php echo tribe_get_start_date(null, false); ?></p>
				<h4>End:</h4>
				<p><?php echo tribe_get_end_date(null, false); ?></p>
				<?php else: ?>
				<h4>Date:</h4>
				<p><?php echo tribe_get_start_date(null, false); ?></p>
				<?php endif; ?>
				<?php if (tribe_get_cost()): ?>
				<h4>Cost:</h4>
				<p><?php echo tribe_get_formatted_cost(); ?></p>
				<?php endif; ?>
				<?php if (tribe_get_event_website_link()): ?>
				<h4>Website:</h4>
				<p><?php echo tribe_get_event_website_link(); ?></p>
				<?php endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-details-->
			<?php if (tribe_get_organizer()): ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-organizer vcard">
				<h3>Organizer</h3>
				<p><?php echo tribe_get_organizer(); ?></p>
				<?php if (tribe_get_organizer_phone()): ?>
				<h4>Phone:</h4>
				<p><?php echo tribe_get_organizer_phone(); ?></p>
				<?php endif; ?>
				<?php if (tribe_get_organizer_email()): ?>
				<h4>Email:</h4>
				<p><?php echo tribe_get_organizer_email(); ?></p>
				<?php endif; ?>
				<?php if (tribe_get_organizer_website_link()): ?>
				<h4>Website:</h4>
				<p><?php echo tribe_get_organizer_website_link(); ?></p>
				<?php endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-organizer.vcard -->
			<?php endif; ?>
			<?php if (tribe_get_venue()): ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-venue vcard">
				<h3>Venue</h3>
				<p><?php echo tribe_get_venue(); ?></p>
				<?php if (tribe_get_phone()): ?>
				<h4>Phone:</h4>
				<p><?php echo tribe_get_phone(); ?></p>
				<?php endif; ?>
				<?php if (tribe_get_address()): ?>
				<p>
					<?php echo tribe_get_address(); ?><br />
					<?php echo tribe_get_city(); ?>, <?php echo tribe_get_state(); ?> <?php echo tribe_get_zip(); ?> <?php echo tribe_get_country(); ?>
					<?php if (tribe_show_google_map_link()): ?><br />
					<a href="<?php echo tribe_get_map_link(); ?>" target="_blank">+ Google Map</a>
					<?php endif; ?>
				</p>
				<?php endif; ?>
				<?php if (tribe_get_venue_website_link()): ?>
				<h4>Website</h4>
				<p><?php echo tribe_get_venue_website_link(); ?></p>
				<?php endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-venue .vcard -->
			<?php endif; ?>
			<?php if (tribe_embed_google_map()): ?>
			<div class="tribe-events-meta-group tribe-events-venue-map">
				<?php echo tribe_get_embedded_map(); ?>
			</div><!--/.tribe-events-meta-group.tribe-events-venue-map -->
			<?php endif; ?>
			<?php do_action("tribe_events_single_event_after_the_meta"); ?>
		</div><!--/.tribe-events-meta-wrapper.tribe-clearfix-->
	</div><!--/.hentry.vevent -->
	<?php
	if (get_post_type() == TribeEvents::POSTTYPE && tribe_get_option("showComments", false )) {
		comments_template();
	}
	?>
	<?php endwhile; ?>
    <div id="tribe-events-footer">
		<h3 class="tribe-events-visuallyhidden"><?php _e("Event Navigation", "tribe-events-calendar") ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link("<span>&laquo;</span> %title%"); ?></li>
			<li class="tribe-events-nav-next"><?php tribe_the_next_event_link("%title% <span>&raquo;</span>"); ?></li>
		</ul><!--/.tribe-events-sub-nav-->
	</div><!--/#tribe-events-footer-->
</div><!--/#tribe-events-content-->
