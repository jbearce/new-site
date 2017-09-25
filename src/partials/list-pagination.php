<?php $pagination_links = paginate_links(array("type" => "array")); ?>

<?php if ($pagination_links): ?>
    <nav class="content_menu-list_container menu-list_container">
        <ul class="menu-list -pagination -center">

            <?php foreach ($pagination_links as $link): ?>
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
