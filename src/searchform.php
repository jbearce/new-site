<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-label text _visuallyhidden" for="s"><?php _e("Search for:", "new-site"); ?></label>
    <input class="search-input input" name="s" title="<?php _e("Search for:", "new-site"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-submit button" type="submit"><i class="fa fa-search"></i><span class="_visuallyhidden"><?php _e("Search", "new-site"); ?></span></button>
</form><!--/.search-form-->
