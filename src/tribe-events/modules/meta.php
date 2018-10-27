<?php
/**
 * Single Event Meta Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta.php
 *
 * @version 4.6.10
 *
 * @package TribeEventsCalendar
 */
?>

<?php do_action( 'tribe_events_single_meta_before' ); ?>

<div class="article__row row row--padded">

    <?php do_action( 'tribe_events_single_event_meta_primary_section_start' ); ?>

    <div class="col-12 col-xs-4">
        <?php
        // Always include the main event details in this first section
        tribe_get_template_part( 'modules/meta/details' );
        ?>
    </div>

    <?php
    // Include organizer meta if appropriate
    if ( tribe_has_organizer() ) {
        echo '<div class="col-12 col-xs-4">';
        tribe_get_template_part( 'modules/meta/organizer' );
        echo "</div>";
    }
    ?>

    <?php
    // Include venue meta if appropriate.
    if ( tribe_get_venue_id() ) {
        echo '<div class="col-12 col-xs-4">';
        tribe_get_template_part( 'modules/meta/venue' );
        echo "</div>";
    }
    ?>

    <?php do_action( 'tribe_events_single_event_meta_primary_section_end' ); ?>

</div>

<?php do_action( 'tribe_events_single_event_meta_secondary_section_start' ); ?>

<?php
// Include google map if appropriate.
if ( tribe_embed_google_map() ) {
    tribe_get_template_part( 'modules/meta/map' );
}
?>

<?php do_action( 'tribe_events_single_event_meta_secondary_section_end' ); ?>

<?php do_action( 'tribe_events_single_meta_after' ); ?>
