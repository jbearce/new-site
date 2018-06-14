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
 * @version  4.6.13
 */
?>

<?php

$filters = tribe_events_get_filters();
$views   = tribe_events_get_views();

$current_url = tribe_events_get_current_filter_url();
?>

<?php do_action( 'tribe_events_bar_before_template' ) ?>
<div class="tribe-events-bar" id="tribe-events-bar">

	<form id="tribe-bar-form" class="tribe-clearfix" name="tribe-bar-form" method="post" action="<?php echo esc_attr( $current_url ); ?>">

        <div class="tribe-events-bar_row row">

            <div class="col-0">
                <!-- Mobile Filters Toggle -->
                <button class="tribe-bar-collapse-toggle" id="tribe-bar-collapse-toggle">
                    <?php printf( esc_html__( 'Find %s', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?> <i class="tribe-bar-collapse-toggle-icon fas fa-caret-down"></i>
                </button>
            </div><!--/.col-0-->

    		<!-- Views -->
    		<?php if ( count( $views ) > 1 ) { ?>
                <div class="col-auto -nogrow -noshrink _flex">
        			<div class="tribe-bar-views" id="tribe-bar-views" style="width: 100%;">
        				<div class="tribe-bar-views-inner tribe-clearfix">
        					<h3 class="tribe-events-visuallyhidden"><?php esc_html_e( 'Event Views Navigation', 'the-events-calendar' ) ?></h3>
        					<label class="tribe-bar-views-label"><?php esc_html_e( 'View As', 'the-events-calendar' ); ?></label>
        					<select
        						class="tribe-bar-views-select tribe-no-param tribe-accessible-js-hidden"
        						name="tribe-bar-view"
        						tabindex="0"
        						aria-label="<?php esc_attr_e( 'View As', 'the-events-calendar' ); ?>"
        					>
        						<?php foreach ( $views as $view ) : ?>
        							<option <?php echo tribe_is_view( $view['displaying'] ) ? 'selected' : 'tribe-inactive' ?> value="<?php echo esc_attr( $view['url'] ); ?>" data-view="<?php echo esc_attr( $view['displaying'] ); ?>">
        								<?php echo $view['anchor']; ?>
        							</option>
        						<?php endforeach; ?>
        					</select>
        				</div>
        				<!-- .tribe-bar-views-inner -->
        			</div><!-- .tribe-bar-views -->
                </div><!--/.col-auto.-nogrow.-noshrink._flex-->
    		<?php } // if ( count( $views ) > 1 ) ?>

        </div><!--/.tribe-events-bar_row.row-->

		<?php if ( ! empty( $filters ) ) { ?>
			<div class="tribe-bar-filters">
				<div class="tribe-bar-filters-inner tribe-clearfix">
                    <div class="tribe-events-bar_row row -padded">
    					<?php foreach ( $filters as $filter ) : ?>
                            <div class="col-12 col-xs-3">
        						<div class="<?php echo esc_attr( $filter['name'] ) ?>-filter">
        							<label class="tribe-bar-filters-label label-<?php echo esc_attr( $filter['name'] ) ?>" for="<?php echo esc_attr( $filter['name'] ) ?>"><?php echo $filter['caption'] ?></label>
        							<?php echo preg_replace('/<input /', '<input class="tribe-bar-filters-input" ', $filter['html']) ?>
        						</div>
                            </div><!--/.col-12.col-xs-3-->
    					<?php endforeach; ?>
                        <div class="col-12 col-xs-auto -nogrow -noshrink">
        					<div class="tribe-bar-submit">
                                <div class="tribe-bar-filters-label">&nbsp; <!-- spacer --> &nbsp;</div>
        						<input class="tribe-events-bar-filters-button tribe-events-button tribe-no-param" type="submit" name="submit-bar" value="<?php printf( esc_attr__( 'Find %s', 'the-events-calendar' ), tribe_get_event_label_plural() ); ?>" />
        					</div>
        					<!-- .tribe-bar-submit -->
                        </div><!--/.col-12.col-xs-auto.-nogrow.-noshrink-->
                    </div><!--/.tribe-events-bar_row.row.-padded-->
				</div>
				<!-- .tribe-bar-filters-inner -->
			</div><!-- .tribe-bar-filters -->
		<?php } // if ( !empty( $filters ) ) ?>

	</form>
	<!-- #tribe-bar-form -->

</div><!-- #tribe-events-bar -->
<?php
do_action( 'tribe_events_bar_after_template' );
