<?php
if (!tribe_get_venue_id()) {
	return;
}

$phone   = tribe_get_phone();
$website = tribe_get_venue_website_link();
?>

<div class="article-col col --half">
	<div class="article__menu-container menu-container">
		<h3 class="article__title title --sub"><?php esc_html_e(tribe_get_venue_label_singular(), "new-site"); ?></h3>
		<ul class="article__menu-list menu-list --meta --vertical">
			<?php do_action( 'tribe_events_single_meta_venue_section_start' ) ?>

	        <li class="article__menu-list__item menu-list__item">
			    <strong class="__bold"> <?php echo tribe_get_venue(); ?></strong>
	        </li>

			<?php if ( tribe_address_exists() ) : ?>
				<li class="article__menu-list__item menu-list__item">
					<address class="address">
						<?php echo tribe_get_full_address(); ?>

						<?php if (tribe_show_google_map_link()): ?>
							<?php echo tribe_get_map_link_html(); ?>
						<?php endif; ?>
					</address>
				</li>
			<?php endif; ?>

			<?php if (!empty($phone)): ?>
	            <li class="article__menu-list__item menu-list__item">
	                <strong class="__bold"><?php esc_html_e("Phone:", "new-site"); ?></strong>
	                <?php echo $phone; ?>
	            </li>
			<?php endif ?>

			<?php if (!empty($website)): ?>
	            <li class="article__menu-list__item menu-list__item">
				    <strong class="__bold"><?php esc_html_e("Website:", "new-site"); ?></strong>
	    			<?php echo $website; ?>
	            </li>
			<?php endif; ?>

			<?php do_action("tribe_events_single_meta_venue_section_end"); ?>
		</ul>
	</div>
</div>
