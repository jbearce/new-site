<?php
if (!defined("ABSPATH")) {
	die("-1");
}

$venue_id = get_the_ID();
$full_region = tribe_get_full_region($venue_id);

// This location's street address.
if (tribe_get_address($venue_id)) {
    echo tribe_get_address($venue_id);

	if (!tribe_is_venue()) {
		echo "<br />";
	}
}

// This locations's city.
if (tribe_get_city($venue_id)) {
	if (tribe_get_address($venue_id)) {
		echo "<br />";
	}

    echo tribe_get_city($venue_id);

    if (tribe_get_region($venue_id)) {
        echo ", ";
    }
}

// This location's abbreviated region. Full region name in the element title.
if (tribe_get_region($venue_id)) {
	echo "<abbr title='" . esc_attr($full_region) . "'>" . tribe_get_region($venue_id) . "</abbr>";

    if (tribe_get_zip($venue_id)) {
        echo " ";
    }
}

// This location's postal code.
if (tribe_get_zip($venue_id)) {
	echo tribe_get_zip($venue_id);

    if (tribe_get_country($venue_id)) {
        echo " ";
    }
}

// This location's country.
if (tribe_get_country($venue_id)) {
	echo tribe_get_country($venue_id);
}
?>
