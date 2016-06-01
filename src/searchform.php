<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-form-text text -label _visuallyhidden" for="s"><?php _e("Search for:", "new-site"); ?></label>
    <input class="search-form-input input -search" name="s" title="<?php _e("Search for:", "new-site"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-form-button button -submit" type="submit"><i class="fa fa-search"></i><span class="_visuallyhidden"><?php _e("Search", "new-site"); ?></span></button>
</form><!--/.search-form-->
