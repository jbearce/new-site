<?
if (get_option("show_on_front") != "page") {
    include(TEMPLATEPATH . "/home.php");
    return;
}
?>
<? get_header(); ?>
		<? if (have_rows("slideshow")): ?>
		<section id="slideshowWrapper">
			<section id="slideshow">
				<?
				while (have_rows("slideshow")) {
					the_row();
					if (get_sub_field("image")) {
						$img = get_sub_field("image");
						$img_cropped = $img["sizes"]["slideshow"];
						echo "<figure>";
						if (get_sub_field("link")) {
							echo "<a href='" . get_sub_field("link") . "'>";
						}
						echo "<img alt='" . $img["alt"] . "' src='" . $img_cropped . "' />";
						if ($img["caption"] != "") {
							echo "<figcaption><p>" . $img["caption"] . "</p></figcaption>";
						}
						if (get_sub_field("link")) {
							echo "</a>";
						}
						echo "</figure>";
					}
				}
				?>
			</section><!--/#slideshow-->
		</section><!--/#slideshowWrapper-->
		<? endif; ?>
		<section id="mainWrapper">
			<main>
				<section id="post">
					<?
					if (have_posts()) {
						while (have_posts()) {
							the_post();
							echo "<article>";
							if (has_post_thumbnail($id)) {
								echo "<figure>" . get_the_post_thumbnail($id, "full") . "</figure>";
							}
							the_title("<header><h1>", "</h1></header>");
							the_content();
							if (comments_open() || get_comments_number() > 0) {
								echo "<footer>";
								comments_template();
								echo "</footer>";
							}
							echo "</article>";
						}
					}
					?>
				</section><!--/#post-->
				<? get_sidebar(); ?>
			</main>
		</section><!--/#mainWrapper-->
<? get_footer(); ?>
