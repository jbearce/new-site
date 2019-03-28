<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @version 4.6.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$event_id    = get_the_ID();

$link        = tribe_get_event_link( $event_id );
$title       = get_the_title( $event_id );
$date        = __gulp_init_namespace___get_tribe_date_and_time_strings( $event_id );
$venue       = tribe_get_venue( $event_id );
$venue_link  = tribe_get_venue_website_link( $event_id, $venue );
$cost        = tribe_get_cost() ? tribe_get_cost( null, true ) : false;

$categories_string = '';

$categories = get_the_terms( $event_id, 'tribe_events_cat' );

if ( $categories ) { $i = 0;
    foreach ( $categories as $category ) { $i++;
        $categories_string .= '<a class="menu-list__link link" href="' . get_term_link( $category->term_id, $category->taxonomy ) . '">' . $category->name . '</a>';

        if ( $i < count( $categories ) ) {
            $categories_string .= ', ';
        }

        if ( $i + 1 == count( $categories ) ) {
            $categories_string .= __( 'and', '__gulp_init_namespace__' ) . ' ';
        }
    }
}
?>

<!-- Event Title -->
<?php do_action( 'tribe_events_before_the_event_title' ) ?>
<h6 class="tribe-events-list-event-title article__title title __nomargin">
	<a class="tribe-event-url title__link link" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
		<?php the_title() ?>
	</a>
</h6>
<?php do_action( 'tribe_events_after_the_event_title' ) ?>

<!-- Event Meta -->
<?php do_action( 'tribe_events_before_the_meta' ) ?>
<div class="tribe-events-event-meta">
	<div class="author <?php echo esc_attr( $has_venue_address ); ?>">

        <nav class="article__menu-list__container menu-list__container">
            <ul class="menu-list menu-list--meta">
                <li class="menu-list__item">
                    <i class="far fa-clock menu-list__icon"></i>
                    <a class="menu-list__link link" href="<?php echo $link; ?>">
                        <?php echo $date[ 'date' ]; ?><?php if ( $date[ 'time' ] ){ echo ', ' . $date[ 'time' ]; } ?>
                    </a>
                </li>

                <?php if ( $venue ) : ?>
                    <li class="menu-list__item">
                        <i class="fas fa-map-marker-alt menu-list__icon"></i>

                        <?php
                        if ( $venue_link ) {
                            echo apply_filters( '__gulp_init_namespace___menu_list_link', $venue_link );
                        } else {
                            echo $venue;
                        }
                        ?>
                    </li><!-- .menu-list_item -->
                <?php endif; ?>

                <?php if ( $cost ) : ?>
                    <li class="menu-list__item">
                        <i class="far fa-money-bill menu-list__icon"></i>
                        <?php echo $cost; ?>
                    </li>
                <?php endif; ?>

                <?php if ( $categories_string ) : ?>
                    <li class="menu-list__item">
                        <i class="fas fa-folder menu-list__icon"></i>
                        <?php echo $categories_string; ?>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

	</div>
</div><!-- .tribe-events-event-meta -->
<?php do_action( 'tribe_events_after_the_meta' ) ?>

<!-- Event Content -->
<?php do_action( 'tribe_events_before_the_content' ); ?>
<div class="tribe-events-list-event-description tribe-events-content description entry-summary">
	<?php echo tribe_events_get_the_excerpt( null, wp_kses_allowed_html( 'post' ) ); ?>
    <p class="article__text text">
        <a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="tribe-events-read-more text__link link" rel="bookmark"><?php esc_html_e( 'Find out more', 'the-events-calendar' ) ?> &raquo;</a>
    </p>
</div><!-- .tribe-events-list-event-description -->
<?php
do_action( 'tribe_events_after_the_content' );
