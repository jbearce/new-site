<?php
// WIP ?>
<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-form_label _visuallyhidden" for="s"><?php _e("Search for:", "joint-forestry-team"); ?></label>
    <input class="search-form_input" name="s" title="<?php _e("Search for:", "joint-forestry-team"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-form_button" type="submit"><i class="fa fa-search"></i><span class="_visuallyhidden"><?php _e("Search", "joint-forestry-team"); ?></span></button>
</form><!--/.search-form-->
