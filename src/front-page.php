<?
if (get_option("show_on_front") != "page") {
    include(TEMPLATEPATH . "/home.php");
    return;
}
?>
<? get_header(); ?>
            <div class="slideshow-wrapper">
                <div class="slideshow">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <figure class="swiper-slide">
                                <img src="http://placehold.it/1200x400" />
                            </figure><!--/.swiper-slide-->
                            <figure class="swiper-slide">
                                <img src="http://dummyimage.com/1200x400/000/fff" />
                            </figure><!--/.swiper-slide-->
                        </div><!--/.swiper-wrapper-->
                    </div><!--/.swiper-container-->
                </div><!--/.slideshow-->
            </div><!--/.slideshowWrapper-->
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
                        <article>
                            <header>
                                <h1>Donec tortor mi, lobortis et fringilla ut, rhoncus vitae turpis</h1>
                            </header>
                            <p>Cras eget orci massa. Maecenas condimentum sapien ipsum, et auctor lacus tristique vel. Ut vel lobortis diam, sed sollicitudin metus. Cras mattis nisl in arcu convallis aliquet. Suspendisse mollis ex eget maximus facilisis. Sed molestie scelerisque lacus, vitae rutrum massa interdum id. Proin condimentum augue vel enim commodo, id egestas massa condimentum. Nulla vel tempor libero, vel efficitur justo. Donec scelerisque blandit enim, sit amet semper turpis hendrerit vehicula. Mauris accumsan urna turpis, consectetur tincidunt massa ultrices in. Aenean sit amet ultrices neque. Cras sapien lectus, ornare ac diam laoreet, fringilla venenatis ex. Mauris tincidunt tristique ante, sed tempus ante.</p>
                        </article>
                    </div><!--/.post-->
                    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
