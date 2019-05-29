<?php
$class           = isset($template_args["class"]) ? $template_args["class"] : "";
$container_class = gettype($class) === "array" && key_exists("container", $class) ? " {$class["container"]}" : (gettype($class) === "string" ? " {$class}" : "");
$list_class      = gettype($class) === "array" && key_exists("list", $class) ? " {$class["list"]}" : "";
$links           = isset($template_args["links"]) ? $template_args["links"] : paginate_links(array("type" => "array"));
?>
<?php if ($links): ?>
    <nav class="menu-list__container<?php echo $container_class; ?>">
        <ul class="menu-list menu-list--pagination<?php echo $list_class; ?>">
            <?php foreach ($links as $link): ?>
                <li class="menu-list__item">
                    <?php echo apply_filters("__gulp_init_namespace___menu_list_link", $link); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
<?php endif; ?>
