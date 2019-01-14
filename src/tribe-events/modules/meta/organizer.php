<?php
/**
 * Single Event Meta (Organizer) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/organizer.php
 *
 * @package TribeEventsCalendar
 * @version 4.4
 */

$event_id = Tribe__Main::post_id_helper();

$organizer_ids = tribe_get_organizer_ids();
$multiple = count( $organizer_ids ) > 1;

$phone   = tribe_get_organizer_phone();
$email   = tribe_get_organizer_email();
$website = tribe_get_event_meta( tribe_get_organizer_id( $event_id ), '_OrganizerWebsite', true );
?>

<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
	<h3 class="tribe-events-single-section-title article__title title title--h6"><?php echo tribe_get_organizer_label( ! $multiple ); ?></h3>
	<ul class="tribe-events-meta-list">
		<?php do_action( 'tribe_events_single_meta_organizer_section_start' ); ?>

		<?php foreach ( $organizer_ids as $organizer ) : ?>
			<?php if ( ! $organizer ) continue; ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-user-circle"></i>
				<?php echo tribe_get_organizer_link( $organizer ) ?>
			</li>
		<?php endforeach; ?>

		<?php if ( ! $multiple ) : // only show organizer details if there is one ?>

            <?php if ( ! empty( $phone ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon fas fa-fw fa-phone"></i>
                    <span class="__visuallyhidden"><?php esc_html_e( 'Phone:', 'the-events-calendar' ) ?></span>
                    <?php echo esc_html( $phone ); ?>
    			</li>
			<?php endif; ?>

			<?php if ( ! empty( $email ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon fas fa-fw fa-envelope"></i>
                    <span class="__visuallyhidden"><?php esc_html_e( 'Email:', 'the-events-calendar' ) ?></span>
                    <?php echo esc_html( $email ); ?>
    			</li>
            <?php endif; ?>

			<?php if ( ! empty( $website ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon fas fa-fw fa-mouse-pointer"></i>
                    <span class="__visuallyhidden"><?php esc_html_e( 'Website:', 'the-events-calendar' ) ?></span>
                    <a class="tribe-events-meta-list-link link link--inherit" href="<?php echo $website; ?>" target="_blank">
                        <?php echo $website; ?>
                    </a>
    			</li>
            <?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'tribe_events_single_meta_organizer_section_end' ); ?>
	</ul>
</div>
