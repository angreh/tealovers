<?php
/*
Template Name: Loja
*/

get_header(); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" class="loja-content" role="main">

            <div class="content_container">

                <?php
                if ( have_posts() ) :
                    // Start the Loop.
                    while ( have_posts() ) :
                        the_post();
                        ?>

<!--                        <h1 class="tmz-woo-loja-h1">--><?php //the_title(); ?><!--</h1>-->

                        <?php the_content(); ?>

                        <?php
                    endwhile;
                // Previous/next post navigation.

                else :
                    // If no content, include the "No posts found" template.
                    echo 'Nenhum post encontrado';

                endif;
                ?>

            </div>


		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_footer();
