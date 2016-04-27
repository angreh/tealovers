<?php get_header(); ?>
    <div class="container wooshop">

    <?php if ( have_posts() ) :

        woocommerce_content();

    endif; ?>

    </div>
<?php get_footer(); ?>