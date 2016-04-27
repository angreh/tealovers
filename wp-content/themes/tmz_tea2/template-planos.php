<?php
/*
Template Name: Planos de assinatura
*/
?>

<?php get_header() ?>
<div class="container">

    <div class="container-planos">

        <?php if ( have_posts() ) :	while ( have_posts() ) : the_post(); ?>

            <?php if(has_post_thumbnail()): ?>
                <div class="post_image">
                    <?php
                    $imgurl = wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() ));
                    ?>
                    <img src="<?php echo $imgurl; ?>" />
                </div>
            <?php endif; ?>

            <h2><?php the_title(); ?></h2>

            <p><?php the_content(); ?></p>

        <?php endwhile; endif; ?>

    </div>

    <div id="planos">

        <?php
        wp_reset_query();
        include('parts/template_planos/planos.php');
        ?>

    </div>

</div>

<?php get_footer(); ?>