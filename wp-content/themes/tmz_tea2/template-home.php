<?php
/*
Template Name: Custom Home
*/
?>
<?php get_header() ?>

<?php include('parts/template_home/banner.php'); ?>

<div id="banner_highlights_separator">
    <hr>
</div>

<?php include('parts/template_home/highlights.php'); ?>

<div id="produtct_titles">
    <div class="container">
        <h1>NOSSOS DESTAQUES</h1>

        <div id="productsIcon"></div>
    </div>
</div>

<div id="products_news_wrapper">
    
    <?php include('parts/template_home/products.php'); ?>
    
    <?php include('parts/template_home/newsletter.php'); ?>

</div>

<?php get_footer(); ?>