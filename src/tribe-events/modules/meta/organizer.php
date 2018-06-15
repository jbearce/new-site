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

$organizer_ids = tribe_get_organizer_ids();
$multiple = count( $organizer_ids ) > 1;

$phone = tribe_get_organizer_phone();
$email = tribe_get_organizer_email();
$website = tribe_get_organizer_website_link();
?>

<div class="tribe-events-meta-group tribe-events-meta-group-organizer">
	<h3 class="tribe-events-single-section-title article_title title -h6"><?php echo tribe_get_organizer_label( ! $multiple ); ?></h3>
	<ul class="tribe-events-meta-list">
		<?php do_action( 'tribe_events_single_meta_organizer_section_start' ); ?>

		<?php foreach ( $organizer_ids as $organizer ) : ?>
			<?php if ( ! $organizer ) continue; ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-user"></i>
				<?php echo tribe_get_organizer_link( $organizer ) ?>
			</li>
		<?php endforeach; ?>

		<?php if ( ! $multiple ) : // only show organizer details if there is one ?>

            <?php if ( ! empty( $phone ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon fas fa-fw fa-phone"></i>
                    <span class="_visuallyhidden"><?php esc_html_e( 'Phone:', 'the-events-calendar' ) ?></span>
                    <?php echo esc_html( $phone ); ?>
    			</li>
			<?php endif; ?>

			<?php if ( ! empty( $email ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon far fa-fw fa-envelope"></i>
                    <span class="_visuallyhidden"><?php esc_html_e( 'Email:', 'the-events-calendar' ) ?></span>
                    <?php echo esc_html( $email ); ?>
    			</li>
            <?php endif; ?>

			<?php if ( ! empty( $website ) ) : ?>
                <li class="tribe-events-meta-list-item">
                    <i class="tribe-events-meta-list-icon fas fa-fw fa-mouse-pointer"></i>
                    <span class="_visuallyhidden"><?php esc_html_e( 'Website:', 'the-events-calendar' ) ?></span>
                    <?php echo preg_replace( '/<a /', '<a class="tribe-events-meta-list-link link -inherit" ', $website ); ?>
    			</li>
            <?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'tribe_events_single_meta_organizer_section_end' ); ?>
	</ul>
</div>
