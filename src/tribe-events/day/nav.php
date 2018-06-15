<?php
/**
 * Day View Nav
 * This file contains the day view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/day/nav.php
 *
 * @package TribeEventsCalendar
 * @version 4.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<h3 class="screen-reader-text _visuallyhidden" tabindex="0"><?php esc_html_e( 'Day Navigation', 'the-events-calendar' ) ?></h3>
<nav class="tribe-events_menu-list_container menu-list_container">
    <ul class="tribe-events-sub-nav menu-list -pagination -flex -between">

    	<!-- Previous Page Navigation -->
    	<li class="tribe-events-nav-previous menu-list_item" aria-label="previous day link">
            <?php tribe_the_day_link( 'previous day' ) ?>
        </li>

    	<!-- Next Page Navigation -->
    	<li class="tribe-events-nav-next menu-list_item" aria-label="next day link">
            <?php tribe_the_day_link( 'next day' ) ?>
        </li>

    </ul>
</nav><!--/.tribe-events_menu-list_container.menu-list_container-->
