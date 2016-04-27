<div id="footer">
    <div class="container">
        <div class="columns-wrapper">
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
        </div><!-- .columns-wrapper -->

        <div id="footer_sociais">
            <a class="sitem inst" target="_blank" href="https://www.instagram.com/tealovers2">Tea Lovers no</a><br>
            <a class="sitem face" target="_blank" href="https://www.facebook.com/Tealovers2">Tea Lovers no</a>
        </div>
        <br style="clear: both">
        <br>
        <hr>
        <div class="politicas">Política de privacidade</div>
        <div class="copyright">tealovers © todos os direitos reservados</div>
        <hr>
        <div id="footer_dados">
            Tea Lovers * 2015 | www.tealovers.com.br | CNPJ: 24.367.251/0001-77<br>
            Tel.: (11) 9 5955-3333 | contato@tealovers.com.br | Rua Batata Ribeiro, 79 - Campinas-SP | CEP 13023-030
        </div>
    </div>
</div>

<script type="text/javascript" src="/wp-content/themes/tmz_tea2/assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/tmz_tea2/assets/js/index.js"></script>
<?php wp_footer(); ?>
</body>
</html>