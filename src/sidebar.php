<?php
$sub_menu = wp_nav_menu(array(
    "container"            => false,
    "direct_parent"        => true,
    "depth"                => 3,
    "echo"                 => false,
    "items_wrap"           => "<nav class='content_menu-list_container menu-list_container'><ul class='menu-list -submenu -vertical'>%3\$s</ul></nav>",
    "only_viewed"          => true,
    "show_parent"          => true,
    "sub_menu"             => true,
    "theme_location"       => "primary",
    "walker"               => new __gulp_init__namespace_menu_walker(),
));

$sub_menu = preg_match_all("/<a/im", $sub_menu, $matches) > 0 ? $sub_menu : false;
?>

<?php if ($sub_menu): ?>
    <div class="col-12 col-xs-4">
        <div class="content_sidebar">
            <?php echo $sub_menu; ?>
        </div><!--/.content_sidebar-->
    </div><!--/.col-12.col-xs-4-->
<?php endif; // ($sub_menu) ?>
