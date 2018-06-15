<?php
/**
 * Single Event Meta (Venue) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/venue.php
 *
 * @package TribeEventsCalendar
 */

if ( ! tribe_get_venue_id() ) {
	return;
}

$event_id = Tribe__Main::post_id_helper();

$phone   = tribe_get_phone();
$website = tribe_get_venue_website_url( $event_id );
?>

<div class="tribe-events-meta-group tribe-events-meta-group-venue">
	<h3 class="tribe-events-single-section-title article_title title -h6"> <?php esc_html_e( tribe_get_venue_label_singular(), 'the-events-calendar' ) ?> </h3>
	<ul class="tribe-events-meta-list">
		<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

        <li class="tribe-events-meta-list-item">
            <i class="tribe-events-meta-list-icon fas fa-fw fa-map-marker-alt"></i>
            <span class="_visuallyhidden"><?php esc_html_e( 'Address:', 'the-events-calendar' ) ?></span>
            <?php echo tribe_get_venue() ?>
            <?php if ( tribe_address_exists() ) : ?>
                <span class="tribe-events-meta-list-small">
                    <?php echo strip_tags( tribe_get_full_address() ); ?>
                </span>
            <?php endif; ?>
        </li>

		<?php if ( ! empty( $phone ) ): ?>
            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-phone"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Phone:', 'the-events-calendar' ) ?></span>
                <?php echo esc_html( $phone ); ?>
            </li>
		<?php endif ?>

		<?php if ( ! empty( $website ) ): ?>
            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-mouse-pointer"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Website:', 'the-events-calendar' ) ?></span>
                <a class="tribe-events-meta-list-link link -inherit" href="<?php echo $website; ?>" target="_blank">
                    <?php echo $website; ?>
                </a>
            </li>
		<?php endif ?>

		<?php do_action( 'tribe_events_single_meta_venue_section_end' ) ?>
	</ul>
</div>
