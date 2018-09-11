<?php
$class = isset($template_args["class"]) ? " {$template_args["class"]}" : "";
$light = isset($template_args["light"]) ? $template_args["light"] : false;
$links = isset($template_args["links"]) ? $template_args["links"] : paginate_links(array("type" => "array"));
?>
<?php if ($links): ?>
    <nav class="menu-list_container<?php echo $class; ?>">
        <ul class="menu-list -pagination -center">
            <?php foreach ($links as $link): ?>
                <?php
                // replace double quote with single quote for consistancy
                $link = preg_replace("/\"/", "'", $link);

                // add necessary classes
                $link = preg_replace("/class=('|\")/", "class='menu-list_link link ", $link);

                // change "current" class to match proper variant structure
                $link = preg_replace("/current/", "-current", $link);
                ?>

                <li class="menu-list_item">
                    <?php echo $link; ?>
                </li><!--/.menu-list_item-->
            <?php endforeach; ?>
        </ul><!--/.menu-list.-pagination.-center-->
    </nav><!--/.contenT_menu-list_container.menu-list_container-->
<?php endif; ?>
