<?php
$map = tribe_get_embedded_map();

if (empty($map)) {
	return;
}
?>

<div class="meta">
	<?php
	// Display the map.
	do_action("tribe_events_single_meta_map_section_start");
	echo $map;
	do_action("tribe_events_single_meta_map_section_end");
	?>
</div>
