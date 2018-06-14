<?php
/**
 * Month View Nav Template
 * This file loads the month view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/month/nav.php
 *
 * @package TribeEventsCalendar
 * @version 4.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<?php do_action( 'tribe_events_before_nav' ) ?>

<h3 class="screen-reader-text _visuallyhidden" tabindex="0"><?php esc_html_e( 'Calendar Month Navigation', 'the-events-calendar' ) ?></h3>

<nav class="tribe-events_menu-list_container menu-list_container">
    <ul class="tribe-events-sub-nav menu-list -pagination -flex -between">
        <li class="tribe-events-nav-previous menu-list_item" aria-label="previous month link">
            <?php tribe_events_the_previous_month_link(); ?>
        </li>
        <!-- .tribe-events-nav-previous -->
        <li class="tribe-events-nav-next menu-list_item" aria-label="next month link">
            <?php tribe_events_the_next_month_link(); ?>
        </li>
        <!-- .tribe-events-nav-next -->
    </ul><!-- .tribe-events-sub-nav -->
</nav><!--/.tribe-events_menu-list_container.menu-list_container-->

<?php
do_action( 'tribe_events_after_nav' );
