<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$style = apply_filters("tribe_events_embedded_map_style", "height: $height; width: $width", $index);
?>

<div id="tribe-events-gmap-<?php esc_attr_e($index); ?>" style="<?php esc_attr_e($style); ?>"></div><!-- #tribe-events-gmap-<?php esc_attr_e($index) ?> -->
