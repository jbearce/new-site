<?php
$i = 0;
$callouts = get_field("callouts");
?>

<?php if ($callouts): ?>
    <?php while (have_rows("callouts")): ?>
        <?php the_row(); ?>

        <?php
        $title = get_sub_field("title");
        $content = get_sub_field("content");
        ?>

        <?php if ($title || $content): ?>
            <?php $i++; ?>

            <?php if ($i === 1 || ($i - 1) % 3 === 0): ?>
            <div class="row -padded">
            <?php endif; ?>

            <div class="col-tablet-6of12 col-notebook-4of12 col-desktop-4of12 -grow _flex">
                <div class="widget" style="min-height:100%;">

                    <?php if ($title): ?>
                    <h6 class="widget_title title"><?php echo $title; ?></h6>
                    <?php endif; ?>
                    <?php if ($content): ?>
                    <div class="widget_content user-content"><?php echo $content; ?></div>
                    <?php endif;?>

                </div><!--/.widget-->
            </div><!--/.col-tablet-4of12._flex-->

            <?php if ($i === count($callouts) || $i % 3 === 0): ?>
            </div><!--/.row.-padded-->
            <?php endif;?>
        <?php endif; // if ($titile || $content) ?>
    <?php endwhile; // while (have_rows("callouts")) ?>
<?php endif; // if ($callouts) ?>
