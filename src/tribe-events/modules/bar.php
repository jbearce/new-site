<?php
/**
 * Events Navigation Bar Module Template
 * Renders our events navigation bar used across our views
 *
 * $filters and $views variables are loaded in and coming from
 * the show funcion in: lib/Bar.php
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/modules/bar.php
 *
 * @package  TribeEventsCalendar
 * @version  4.6.26
 */
?>

<?php

$filters     = tribe_events_get_filters();
$views       = tribe_events_get_views();
$current_url = tribe_events_get_current_filter_url();
$classes     = array( 'tribe-clearfix', 'tribe-bar-form' );

if ( ! empty( $filters ) ) {
	$classes[] = 'tribe-events-bar--has-filters';
}

if ( count( $views ) > 1 ) {
	$classes[] = 'tribe-events-bar--has-views';
}

?>

<?php do_action( 'tribe_events_bar_before_template' ) ?>
<div class="tribe-events-bar" id="tribe-events-bar">

	<h2 class="tribe-events-visuallyhidden __visuallyhidden"><?php printf( esc_html__( '%s Search and Views Navigation', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?></h2>

	<form id="tribe-bar-form" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" name="tribe-bar-form" method="post" action="<?php echo esc_attr( $current_url ); ?>">

		<?php if ( ! empty( $filters ) ) : ?>
			<div class="tribe-bar-filters-wrap" id="tribe-bar-filters-wrap">

				<button class="tribe-bar-collapse-toggle" id="tribe-bar-collapse-toggle" aria-expanded="false" type="button" aria-controls="tribe-bar-filters" data-label-hidden="<?php printf( esc_html__( 'Show %s Search', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>" data-label-shown="<?php printf( esc_html__( 'Hide %s Search', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>">
					<span class="tribe-bar-toggle-text">
						<?php printf( esc_html__( 'Show %s Search', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>
					</span>
					<i class="tribe-bar-collapse-toggle-icon fas fa-caret-down"></i>
				</button>

				<div id="tribe-bar-filters"  class="tribe-bar-filters" aria-hidden="true">
					<div class="tribe-bar-filters-inner tribe-clearfix">
						<h3 class="tribe-events-visuallyhidden __visuallyhidden"><?php printf( esc_html__( '%s Search', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?></h3>
						<?php foreach ( $filters as $filter ) : ?>
							<div class="tribe-bar-filters-filter <?php echo esc_attr( $filter['name'] ) ?>-filter">
								<label class="tribe-bar-filters-label label-<?php echo esc_attr( $filter['name'] ) ?>" for="<?php echo esc_attr( $filter['name'] ) ?>"><?php echo $filter['caption'] ?></label>
								<?php echo apply_filters( '__gulp_init_namespace___tribe_add_bar_input_class', $filter['html'] ); ?>
							</div>
						<?php endforeach; ?>
						<input class="tribe-bar-filters-button tribe-events-button tribe-no-param" type="submit" name="submit-bar" aria-label="<?php printf( esc_attr__( 'Submit %s search', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>" value="<?php printf( esc_attr__( 'Find %s', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>" />
					</div><!-- .tribe-bar-filters-inner -->
				</div><!-- .tribe-bar-filters -->
			</div><!-- .tribe-bar-filters-wrap -->
		<?php endif; // ( !empty( $filters ) ) ?>

		<?php if ( count( $views ) > 1 ) : ?>
			<div id="tribe-bar-views" class="tribe-bar-views">
				<div class="tribe-bar-views-inner tribe-clearfix">
					<h3 class="tribe-events-visuallyhidden __visuallyhidden"><?php printf( esc_html__( '%s Views Navigation', 'the-events-calendar' ), tribe_get_event_label_singular() ); ?></h3>
					<label class="tribe-bar-views-label" id="tribe-bar-views-label" aria-label="<?php printf( esc_html__( 'View %s As', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>">
						<?php esc_html_e( 'View As', 'the-events-calendar' ); ?>
					</label>
					<select class="tribe-bar-views-select tribe-no-param __visuallyhidden" name="tribe-bar-view" aria-label="<?php printf( esc_attr__( 'View %s As', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>">
						<?php
						foreach ( $views as $view ) {
							printf(
								'<option value="%1$s" data-view="%2$s"%3$s>%4$s</option>',
								esc_attr( $view['url'] ),
								esc_attr( $view['displaying'] ),
								tribe_is_view( $view['displaying'] ) ? ' selected' : '',
								esc_html( $view['anchor'] )
							);
						}
						?>
					</select>
				</div>
			</div>
		<?php endif; // (count( $views ) > 1) ?>

	</form><!-- #tribe-bar-form -->

</div><!-- #tribe-events-bar -->
<?php
do_action( 'tribe_events_bar_after_template' );
