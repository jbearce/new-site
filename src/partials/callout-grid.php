<?php
$callouts = get_field("callouts");

if ($callouts) {
    $i = 0;

    while (have_rows("callouts")) {
        the_row();

        $title = get_sub_field("title");
        $content = get_sub_field("content");

        if ($title || $content) {
            $i++;

            if ($i === 1 || ($i - 1) % 3 === 0) {
                echo "<div class='row -padded -equalheight'>";
            }

            echo "<div class='col -third'><div class='widget'>";

            if ($title) {
                echo "<h6 class='widget_title title'>{$title}</h6>";
            }

            if ($content) {
                echo "<div class='widget_content user-content'>{$content}</div>";
            }

            echo "</div></div>";

            if ($i === count($callouts) || $i % 3 === 0) {
                // close row -padded
                echo "</div>";
            }
        }
    }
}
?>
