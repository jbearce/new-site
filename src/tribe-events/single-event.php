<? if (!defined("ABSPATH")) {die("-1");} ?>
<? $event_id = get_the_ID(); ?>
<div id="tribe-events-content" class="tribe-events-single">
	<p class="tribe-events-back"><a href="<? echo tribe_get_events_link(); ?>"> <? _e("&laquo; All Events", "tribe-events-calendar"); ?></a></p>
	<? tribe_events_the_notices() ?>
	<? the_title("<h2 class='tribe-events-single-event-title summary'>", "</h2>"); ?>
	<div class="tribe-events-schedule updated published tribe-clearfix">
		<h3>
			<? echo tribe_events_event_schedule_details($event_id); ?>
			<? if (tribe_get_cost()):  ?>
				<span class="tribe-events-divider">|</span>
				<span class="tribe-events-cost"><? echo tribe_get_cost(null, true); ?></span>
			<? endif; ?>
		</h3>
	</div><!--/.tribe-events-schedule.updated.published.tribe-clearfix-->
	<div id="tribe-events-header" <? tribe_events_the_header_attributes(); ?>>
		<h3 class="tribe-events-visuallyhidden"><? _e("Event Navigation", "tribe-events-calendar"); ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><? tribe_the_prev_event_link("<span>&laquo;</span> %title%") ?></li>
			<li class="tribe-events-nav-next"><? tribe_the_next_event_link("%title% <span>&raquo;</span>") ?></li>
		</ul><!--/.tribe-events-sub-nav-->
	</div><!--/#tribe-events-header-->
	<? while (have_posts()):  the_post(); ?>
	<div id="post-<? the_ID(); ?>" <? post_class("vevent"); ?>>
		<? echo tribe_event_featured_image(); ?>
		<? do_action("tribe_events_single_event_before_the_content") ?>
		<div class="tribe-events-single-event-description tribe-events-content entry-content description">
			<? the_content(); ?>
		</div><!--/.tribe-events-single-event-description.tribe-events-content.entry-content.description-->
		<? do_action("tribe_events_single_event_after_the_content"); ?>
		<div class="tribe-events-meta-wrapper tribe-clearfix">
			<? do_action("tribe_events_single_event_before_the_meta"); ?>
			<? // echo tribe_events_single_event_meta() ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-details">
				<h3>Details</h3>
				<? if (tribe_get_start_date(null, false) != tribe_get_end_date(null, false)): ?>
				<h4>Start:</h4>
				<p><? echo tribe_get_start_date(null, false); ?></p>
				<h4>End:</h4>
				<p><? echo tribe_get_end_date(null, false); ?></p>
				<? else: ?>
				<h4>Date:</h4>
				<p><? echo tribe_get_start_date(null, false); ?></p>
				<? endif; ?>
				<? if (tribe_get_cost()): ?>
				<h4>Cost:</h4>
				<p><? echo tribe_get_formatted_cost(); ?></p>
				<? endif; ?>
				<? if (tribe_get_event_website_link()): ?>
				<h4>Website:</h4>
				<p><? echo tribe_get_event_website_link(); ?></p>
				<? endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-details-->
			<? if (tribe_get_organizer()): ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-organizer vcard">
				<h3>Organizer</h3>
				<p><? echo tribe_get_organizer(); ?></p>
				<? if (tribe_get_organizer_phone()): ?>
				<h4>Phone:</h4>
				<p><? echo tribe_get_organizer_phone(); ?></p>
				<? endif; ?>
				<? if (tribe_get_organizer_email()): ?>
				<h4>Email:</h4>
				<p><? echo tribe_get_organizer_email(); ?></p>
				<? endif; ?>
				<? if (tribe_get_organizer_website_link()): ?>
				<h4>Website:</h4>
				<p><? echo tribe_get_organizer_website_link(); ?></p>
				<? endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-organizer.vcard -->
			<? endif; ?>
			<? if (tribe_get_venue()): ?>
			<div class="tribe-events-meta-group tribe-events-meta-group-venue vcard">
				<h3>Venue</h3>
				<p><? echo tribe_get_venue(); ?></p>
				<? if (tribe_get_phone()): ?>
				<h4>Phone:</h4>
				<p><? echo tribe_get_phone(); ?></p>
				<? endif; ?>
				<? if (tribe_get_address()): ?>
				<p>
					<? echo tribe_get_address(); ?><br />
					<? echo tribe_get_city(); ?>, <? echo tribe_get_state(); ?> <? echo tribe_get_zip(); ?> <? echo tribe_get_country(); ?>
					<? if (tribe_show_google_map_link()): ?><br />
					<a href="<? echo tribe_get_map_link(); ?>" target="_blank">+ Google Map</a>
					<? endif; ?>
				</p>
				<? endif; ?>
				<? if (tribe_get_venue_website_link()): ?>
				<h4>Website</h4>
				<p><? echo tribe_get_venue_website_link(); ?></p>
				<? endif; ?>
			</div><!--/.tribe-events-meta-group.tribe-events-meta-group-venue .vcard -->
			<? endif; ?>
			<? if (tribe_embed_google_map()): ?>
			<div class="tribe-events-meta-group tribe-events-venue-map">
				<? echo tribe_get_embedded_map(); ?>
			</div><!--/.tribe-events-meta-group.tribe-events-venue-map -->
			<? endif; ?>
			<? do_action("tribe_events_single_event_after_the_meta"); ?>
		</div><!--/.tribe-events-meta-wrapper.tribe-clearfix-->
	</div><!--/.hentry.vevent -->
	<?
	if (get_post_type() == TribeEvents::POSTTYPE && tribe_get_option("showComments", false )) {
		comments_template();
	}
	?>
	<? endwhile; ?>
    <div id="tribe-events-footer">
		<h3 class="tribe-events-visuallyhidden"><? _e("Event Navigation", "tribe-events-calendar") ?></h3>
		<ul class="tribe-events-sub-nav">
			<li class="tribe-events-nav-previous"><? tribe_the_prev_event_link("<span>&laquo;</span> %title%"); ?></li>
			<li class="tribe-events-nav-next"><? tribe_the_next_event_link("%title% <span>&raquo;</span>"); ?></li>
		</ul><!--/.tribe-events-sub-nav-->
	</div><!--/#tribe-events-footer-->
</div><!--/#tribe-events-content-->
