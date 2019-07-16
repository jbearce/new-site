<?php
$class           = isset($this->vars["class"]) ? $this->vars["class"] : "";
$container_class = gettype($class) === "array" && key_exists("container", $class) ? " {$class["container"]}" : (gettype($class) === "string" ? " {$class}" : "");
$list_class      = gettype($class) === "array" && key_exists("list", $class) ? " {$class["list"]}" : "";
$light           = isset($this->vars["light"]) ? $this->vars["light"] : false;
$links           = isset($this->vars["links"]) ? $this->vars["links"] : paginate_links(array("type" => "array"));
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
