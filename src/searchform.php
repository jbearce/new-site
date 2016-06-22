<?php $search_query = is_search() ? get_search_query() : ""; ?>
<form class="search-form" action="<?php echo home_url(); ?>" method="get">
    <label class="search-form_text text -label _visuallyhidden" for="s"><?php _e("Search for:", "new_site"); ?></label>
    <input class="search-form_input input -search" name="s" title="<?php _e("Search for:", "new_site"); ?>" type="search" value="<?php echo $search_query; ?>" />
    <button class="search-form_button button -submit" type="submit"><i class="fa fa-search"></i><span class="_visuallyhidden"><?php _e("Search", "new_site"); ?></span></button>
</form><!--/.search-form-->
