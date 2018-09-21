<?php
$sub_menu = wp_nav_menu(array(
    "container"            => false,
    "direct_parent"        => true,
    "depth"                => 3,
    "echo"                 => false,
    "items_wrap"           => "<ul class='menu-list --submenu --vertical'>%3\$s</ul>",
    "show_parent"          => false,
    "sub_menu"             => true,
    "theme_location"       => "primary",
    "tree_mode"            => "viewed",
    "walker"               => new __gulp_init__namespace_menu_walker(),
));

$sub_menu = preg_match_all("/<a/im", $sub_menu, $matches) > 0 ? $sub_menu : false;
?>

<?php if ($sub_menu): ?>
    <div class="col-12 col-xs-4">
        <div class="content__sidebar">
            <nav class="content__menu-list_container menu-list__container">
                <?php echo $sub_menu; ?>
            </nav><!--/.content_menu-list__container.menu-list__container-->
        </div><!--/.content__sidebar-->
    </div><!--/.col-12.col-xs-4-->
<?php endif; // ($sub_menu) ?>
