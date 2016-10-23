<?php
$pagination_links = paginate_links(array("type" => "array"));

if ($pagination_links) {
    echo "<nav class=content_menu-list_container menu-list_container'><ul class='menu-list -pagination -center'>";

    foreach ($pagination_links as $link) {
        // replace double quote with single quote for consistancy
        $link = preg_replace("/\"/", "'", $link);

        // add necessary classes
        $link = preg_replace("/class=('|\")/", "class='menu-list_link link ", $link);

        // change "current" class to match proper variant structure
        $link = preg_replace("/current/", "-current", $link);

        echo "<li class='menu-list_item'>{$link}</li>";
    }

    echo "</ul></nav>";
}
?>
