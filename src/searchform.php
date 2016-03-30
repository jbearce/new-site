<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-label content" for="s"><?php _e("Search for:", "new-site"); ?></label>
    <input class="search-input input" name="s" title="<?php _e("Search for:", "new-site"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-submit button" type="submit"><?php _e("Search", "new-site"); ?></button>
</form><!--/.search-form-->
