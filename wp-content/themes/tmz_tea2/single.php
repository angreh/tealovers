<?php get_header(); ?>
    <div class="container">

        <div class="small_container">
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
            <small><?php the_time('F jS, Y'); ?></small>

            <div class="entry">
                <?php the_content(); ?>
            </div>

            <!-- <p class="postmetadata"><?php _e( 'Posted in' ); ?> <?php the_category( ', ' ); ?></p> -->

        <?php endwhile; endif; ?>
        </div><!-- .small_container -->

    </div><!-- .container -->
<?php get_footer(); ?>