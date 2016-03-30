<?php
$filters = tribe_events_get_filters();
$views   = tribe_events_get_views();
$current_url = tribe_events_get_current_filter_url();
?>

<?php do_action("tribe_events_bar_before_template"); ?>

<div id="tribe-events-bar">

	<form id="tribe-bar-form" class="tribe-clearfix" name="tribe-bar-form" method="post" action="<?php esc_attr_e($current_url); ?>">

		<!-- Mobile Filters Toggle -->

		<div id="tribe-bar-collapse-toggle" <?php if (count($views) == 1): ?> class="tribe-bar-collapse-toggle-full-width"<?php endif; ?>>
			<?php printf(esc_html__("Find %s", "new-site"), tribe_get_event_label_plural()); ?><span class="tribe-bar-toggle-arrow"></span>
		</div>

		<!-- Views -->
		<?php if (count($views) > 1) { ?>
			<div id="tribe-bar-views">
				<div class="tribe-bar-views-inner tribe-clearfix">
					<h3 class="tribe-events-visuallyhidden"><?php esc_html_e("Event Views Navigation", "new-site"); ?></h3>
					<label><?php esc_html_e("View As", "new-site"); ?></label>
					<select class="tribe-bar-views-select tribe-no-param" name="tribe-bar-view">
						<?php foreach ($views as $view): ?>
							<option <?php echo tribe_is_view($view["displaying"]) ? "selected" : "tribe-inactive" ?> value="<?php esc_attr_e($view["url"]); ?>" data-view="<?php esc_attr_e($view["displaying"]); ?>">
								<?php echo $view["anchor"]; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<!-- .tribe-bar-views-inner -->
			</div><!-- .tribe-bar-views -->
		<?php } // if (count($views) > 1) ?>

		<?php if (!empty($filters)) { ?>
			<div class="tribe-bar-filters">
				<div class="tribe-bar-filters-inner tribe-clearfix">
					<?php foreach ($filters as $filter): ?>
						<div class="<?php esc_attr_e($filter["name"]); ?>-filter">
							<label class="label-<?php esc_attr_e($filter["name"]); ?>" for="<?php esc_attr_e($filter["name"]); ?>"><?php echo $filter["caption"]; ?></label>
							<?php echo $filter["html"]; ?>
						</div>
					<?php endforeach; ?>
					<div class="tribe-bar-submit">
						<input class="tribe-events-button tribe-no-param" type="submit" name="submit-bar" value="<?php printf(esc_attr__("Find %s", "new-site"), tribe_get_event_label_plural()); ?>" />
					</div>
					<!-- .tribe-bar-submit -->
				</div>
				<!-- .tribe-bar-filters-inner -->
			</div><!-- .tribe-bar-filters -->
		<?php } // if (!empty($filters)) ?>

	</form>
	<!-- #tribe-bar-form -->

</div><!-- #tribe-events-bar -->
<?php do_action("tribe_events_bar_after_template"); ?>
