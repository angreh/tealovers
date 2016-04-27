<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
		</div><!-- #main -->

	</div><!-- #page -->
<footer>

    <div class="content_container">
        <div class="columns">
            <span class="footer_title">FALE CONOSCO</span><br />
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer_fale',
                        'menu_class' => 'footer-item',
                        'menu' => 'footer-fale'
                    )
                );
            ?>
        </div>

        <div class="columns">
            <span class="footer_title">SOBRE A TEA LOVERS</span><br />
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer_fale',
                    'menu_class' => 'footer-item',
                    'menu' => 'footer-sobre'
                )
            );
            ?>
        </div>

        <div class="columns">
            <span class="footer_title">FORMAS DE PAGAMENTO</span><br />
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer_fale',
                    'menu_class' => 'footer-item',
                    'menu' => 'footer-formas'
                )
            );
            ?>
        </div>

        <div class="columns">
            TEA LOVERS SOCIAL MEDIA<br />
            logos
        </div>

        <div class="footer_down">
            <div class="left">Políticas de privacidade</div>
            <div class="right">&COPY; todos os direitos reservados</div>
        </div>
    </div>

</footer>

	<?php wp_footer(); ?>
</body>
</html>