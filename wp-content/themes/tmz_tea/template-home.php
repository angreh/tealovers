<?php
/*
Template Name: Home Main Banner
*/
?>
<?php get_header(); ?>

<div id="main-content" class="main-content">
    <div class="content_container">

        <?php include('parts/template-home/home-slider.php'); ?>

        <?php include('parts/template-home/home-destaques.php'); ?>

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">

                <?php include('parts/template-home/home-posts.php'); ?>

                <?php include('parts/template-home/home-products.php'); ?>

            </div><!-- #content -->
        </div><!-- #primary -->

    </div><!-- .content_container -->
</div><!-- #main-content -->

<?php  get_footer();
