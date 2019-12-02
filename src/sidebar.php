<?php
$sub_menu = has_nav_menu("primary") ? wp_nav_menu([
    "container"      => false,
    "depth"          => 3,
    "echo"           => false,
    "items_wrap"     => "<ul class='menu-list menu-list--submenu menu-list--vertical'>%3\$s</ul>",
    "show_parent"    => false,
    "sub_menu"       => true,
    "theme_location" => "primary",
    "tree_mode"      => "viewed",
    "walker"         => new __gulp_init_namespace___menu_walker(),
]) : false;
?>

<?php if ($sub_menu): ?>
    <div class="col-12 col-xs-4">
        <div class="content__sidebar">
            <nav class="content__menu-list__container menu-list__container">
                <?php echo $sub_menu; ?>
            </nav>
        </div>
    </div>
<?php endif; ?>
