<?php
/**
 * Single Event Meta (Details) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/details.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.18
 */

$event_id = Tribe__Main::post_id_helper();

$date    = get_tribe_date_and_time_strings( $event_id );
$cost    = tribe_get_cost() ? tribe_get_cost( null, true ) : false;
$website = strip_tags( tribe_get_event_website_link() );

$categories_string = '';

$categories = get_the_terms( $event_id, 'tribe_events_cat' );

if ( $categories ) { $i = 0;
    foreach ( $categories as $category ) { $i++;
        $categories_string .= '<a class="tribe-events-meta-list-link link -inherit" href="' . get_term_link( $category->term_id, $category->taxonomy ) . '">' . $category->name . '</a>';

        if ( $i < count( $categories ) ) {
            $categories_string .= ', ';
        }

        if ( $i + 1 == count( $categories ) ) {
            $categories_string .= __( 'and', '__gulp_init__namespace' ) . ' ';
        }
    }
}

$tags_string = '';

$tags = get_the_terms( $event_id, 'post_tag' );

if ( $tags ) { $i = 0;
    foreach ( $tags as $tag ) { $i++;
        $tags_string .= '<a class="tribe-events-meta-list-link link -inherit" href="' . get_term_link( $tag->term_id, $tag->taxonomy ) . '">' . $tag->name . '</a>';

        if ( $i < count( $tags ) ) {
            $tags_string .= ', ';
        }

        if ( $i + 1 == count( $tags ) ) {
            $tags_string .= __( 'and', '__gulp_init__namespace' ) . ' ';
        }
    }
}
?>

<div class="tribe-events-meta-group tribe-events-meta-group-details">
	<h3 class="tribe-events-single-section-title article_title title -h6"> <?php esc_html_e( 'Details', 'the-events-calendar' ) ?> </h3>
	<ul class="tribe-events-meta-list">

		<?php do_action( 'tribe_events_single_meta_details_section_start' ); ?>

        <?php if ( $date[ 'date' ] ) : ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon far fa-fw fa-clock"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Date:', 'the-events-calendar' ) ?></span>
                <?php echo $date[ 'date' ]; ?>
                <?php if ( $date[ 'time' ] ) : ?>
                    <span class="tribe-events-meta-list-small">
                        <?php echo $date[ 'time' ]; ?>
                    </span>
                <?php endif; ?>
            </li>

        <?php endif; ?>

        <?php if ( $cost ) : ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon far fa-fw fa-money-bill"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Cost:', 'the-events-calendar' ) ?></span>
                <?php echo $cost; ?>
            </li>

        <?php endif; ?>

        <?php if ( $website ) : ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-mouse-pointer"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Website:', 'the-events-calendar' ) ?></span>
                <a class="tribe-events-meta-list-link link -inherit">
                    <?php echo $website; ?>
                </a>
            </li>

        <?php endif; ?>

        <?php if ( $categories_string ) : ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon fas fa-fw fa-folder"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Categories:', 'the-events-calendar' ) ?></span>
                <?php echo $categories_string; ?>
            </li>

        <?php endif; ?>

        <?php if ( $tags_string ) : ?>

            <li class="tribe-events-meta-list-item">
                <i class="tribe-events-meta-list-icon far fa-fw fa-tag"></i>
                <span class="_visuallyhidden"><?php esc_html_e( 'Tags:', 'the-events-calendar' ) ?></span>
                <?php echo $tags_string; ?>
            </li>

        <?php endif; ?>

		<?php do_action( 'tribe_events_single_meta_details_section_end' ) ?>
	</ul>
</div>
