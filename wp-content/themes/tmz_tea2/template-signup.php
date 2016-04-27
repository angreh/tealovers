<?php
/*
Template Name: Cadastre-se
*/
?>
<?php get_header(); ?>
    <div class="container">
        <div id="signup_div">

            <?php if ( have_posts() ) :	while ( have_posts() ) : the_post(); ?>

                <div class="entry">
                    <?php the_content(); ?>
                </div>

            <?php endwhile; endif; ?>

        </div>
    </div>
<?php get_footer(); ?>