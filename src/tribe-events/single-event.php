<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.3
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

$date = __gulp_init_namespace___get_tribe_date_and_time_strings( $event_id );
$cost = tribe_get_cost() ? tribe_get_cost( null, true ) : false;
?>

<article id="tribe-events-content" class="tribe-events-single tribe-events-article article">

	<p class="tribe-events-back tribe-events__text text">
		<a class="text__link link" href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'the-events-calendar' ), $events_label_plural ); ?></a>
	</p>

	<!-- Notices -->
	<?php tribe_the_notices() ?>

	<?php the_title( '<h1 class="tribe-events-single-event-title article__title title">', '</h1>' ); ?>

    <nav class="article__menu-list__container menu-list__container">
        <ul class="menu-list menu-list--meta">
            <li class="menu-list__item">
                <i class="far fa-clock menu-list__icon"></i>
                <?php echo $date[ 'date' ]; ?><?php if ( $date[ 'time' ] ){ echo ', ' . $date[ 'time' ]; } ?>
            </li>

            <?php if ( $cost ) : ?>
                <li class="menu-list__item">
                    <i class="far fa-money-bill menu-list__icon"></i>
                    <?php echo $cost; ?>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'full', false ); ?>

			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content">
				<?php the_content(); ?>
			</div>
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>

			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

</article><!-- #tribe-events-content -->
