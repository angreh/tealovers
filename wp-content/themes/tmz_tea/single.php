<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header();

if ( have_posts() ) :
    the_post();
?>


<div class="content_container">
    <div id="main-content" class="main-content">

        <div id="primary" class="content-area">
            <div id="content" class="site-content" role="main">

                <?php if(has_post_thumbnail()): ?>
                    <div class="post_image">
                        <?php
                        $imgurl = wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() ));
                        ?>
                        <img src="<?php echo $imgurl; ?>" />
                    </div>
                <?php endif; ?>

                <div class="post_title">
                    <?php the_title(); ?>
                </div>

                <div class="post_content">
                    <?php the_content(); ?>
                </div>

            </div><!-- #content -->
        </div><!-- #primary -->
    </div><!-- #main-content -->
</div>
<?php

endif;

get_footer();
