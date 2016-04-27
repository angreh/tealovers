<?php
if (!defined('ABSPATH'))
	exit;

/**
 * WC AZPay Lite Creditcard
 * Payment Gateway
 *
 * Provides a creditcard payment method,
 * integrated with AZPay Gateway.
 *
 * @class 		WC_AZPay_Lite_Creditcard
 * @extends		WC_Payment_Gateway
 * @author 		Gabriel Guerreiro (gabrielguerreiro.com)
 */

class WC_AZPay_Lite_Creditcard extends WC_Payment_Gateway {

	public $creditcard_config = null;

	public function __construct() {

		$this->id           = 'azpay_lite_creditcard';
		$this->icon         = null;
		$this->has_fields   = true;
		$this->method_title = 'AZPay Lite - Cartão de Crédito';
		$this->title = 'Cartão de Crédito';

		$this->init_form_fields();
		$this->init_settings();

	    foreach ($this->settings as $setting_key => $value) {
	        $this->$setting_key = $value;
	    }

		add_action('admin_notices', array($this, 'admin_notices'));

		if (is_admin()) {

			wp_enqueue_style('azpay-lite', plugins_url('assets/css/style.css', plugin_dir_path(__FILE__)));

	        if (version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
                add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
	        	add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'save_creditcard_config'));
            } else {
                add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
	        	add_action('woocommerce_update_options_payment_gateways', array(&$this, 'save_creditcard_config'));
            }
	    }
	}

	/**
	 * Displays notifications when the admin has something wrong with the configuration.
	 *
	 * @return void
	 */
	public function admin_notices() {
		if (is_admin()) {
			if (get_woocommerce_currency() != 'BRL')
				add_action('admin_notices', array($this, 'currency_not_supported_message'));
		}
	}


	/**
	 * Admin Panel Options.
	 *
	 * @return string Admin form.
	 */
	public function admin_options() {

		// Generate the HTML For the settings form.
		echo '<table class="azpay-form-admin">';
			$this->generate_settings_html();
		echo '</table>';
	}

	/**
	 * Start Gateway Settings Form Fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {

		$fields = array(

			// AZPay Config
			'azpay_lite_title' => array(
				'title' => 'Woocommerce AZPay Lite - Cartão de Crédito',
				'type'  => 'title'
			),
			'enabled' => array(
				'title'   => 'Habilitar/Desabilitar',
				'type'    => 'checkbox',
				'default' => 'yes'
			),

			'creditcard_form_title' => array(
				'title'       => 'Título',
				'type'        => 'text',
				'description' => 'Título da forma de pagamento que aparece para o usuário',
				'desc_tip'    => true,
			),

			'creditcard_form_description' => array(
				'title'       => 'Descrição',
				'type'        => 'textarea',
				'description' => 'Descrição da forma de pagamento que aparece para o usuário',
				'desc_tip'    => true,
			),

			// AZPay Config
			'config' => array(
				'title' => 'Configurando AZPay',
				'type'  => 'title'
			),
			'merchant_id' => array(
				'title'       => 'Merchant ID',
				'type'        => 'text',
				'description' => 'ID da sua conta no AZPay',
				'desc_tip'    => true,
			),
			'merchant_key' => array(
				'title'       => 'Merchant Key',
				'type'        => 'text',
				'description' => 'Chave da sua conta no AZPay',
				'desc_tip'    => true,
			),
			'clearsale' => array(
				'title'       => 'Clearsale',
				'type'        => 'checkbox',
				'default'     => 'no',
				'description' => 'Solução antifraude ClearSale (Contatar equipe técnica AZPay para utilizar)',				
			),
			'auto_capture' => array(
				'title'       => 'Captura Automática',
				'type'        => 'checkbox',
				'default'     => 'no',
				'description' => 'Essa opção ativa a mudança automática do status do pedido, a partir das respostas do AZPay',				
			),
			'orderstatus_title' => array(
				'title' => 'Configurando troca de status dos pedidos conforme retorno AZPay',
				'type'  => 'title'
			),
			'azpaystatus_approved' => array(
				'title'       => 'Transação aprovada',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna aprovada',
				'default'     => 'completed',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			'azpaystatus_capturing' => array(
				'title'       => 'Transação capturada / aguardando pagamento',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna capturada / aguardando pagamento',
				'default'     => 'processing',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			'azpaystatus_cancelled' => array(
				'title'       => 'Transação cancelada',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna cancelada',
				'default'     => 'cancelled',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			'azpaystatus_unauthenticated' => array(
				'title'       => 'Cartão não autenticado',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna cartão não autenticado',
				'default'     => 'failed',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			'azpaystatus_unauthorized' => array(
				'title'       => 'Transação não autorizada pela operadora',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna transação não autorizada pela operadora',
				'default'     => 'failed',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			'azpaystatus_unapproved' => array(
				'title'       => 'Transação não capturada',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Quando transação feita pelo azpay retorna transação não capturada',
				'default'     => 'on-hold',
				'options'     => array(
					'completed' => 'Completado',
					'processing' => 'Processando',
					'cancelled' => 'Cancelado',
					'failed' => 'Falho',
					'on-hold' => 'Aguardando',
				)
			),
			// Creditcard
			'creditcard_title' => array(
				'title' => 'Configurando bandeiras e operadoras',
				'type'  => 'title'
			),
			// Visa
			'visa_title' => array(
				'title' => 'Visa',
				'type'  => 'title'
			),
			'visa_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Visa',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
					'3' => 'Redecard - Komerci WebService',
					'4' => 'Redecard - Komerci Integrado',
					'6' => 'Elavon',
					'20' => 'Stone',
				)
			),
			'visa_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'visa_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// Mastercard
			'mastercard_title' => array(
				'title' => 'Mastercard',
				'type'  => 'title'
			),
			'mastercard_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Mastercard',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
					'3' => 'Redecard - Komerci WebService',
					'4' => 'Redecard - Komerci Integrado',
					'6' => 'Elavon',
					'20' => 'Stone',
				)
			),
			'mastercard_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'mastercard_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// Amex
			'amex_title' => array(
				'title' => 'Amex',
				'type'  => 'title'
			),
			'amex_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Amex',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'amex_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'amex_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// Diners
			'diners_title' => array(
				'title' => 'Diners',
				'type'  => 'title'
			),
			'diners_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Diners',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'diners_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'diners_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// Discover
			'discover_title' => array(
				'title' => 'Discover',
				'type'  => 'title'
			),
			'discover_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Discover',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'discover_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'discover_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// ELO
			'elo_title' => array(
				'title' => 'ELO',
				'type'  => 'title'
			),
			'elo_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira ELO',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'elo_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'elo_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// Aura
			'aura_title' => array(
				'title' => 'Aura',
				'type'  => 'title'
			),
			'aura_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira Aura',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'aura_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'aura_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

			// JCB
			'jcb_title' => array(
				'title' => 'JCB',
				'type'  => 'title'
			),
			'jcb_acquirer' => array(
				'title'       => 'Operadora / Adquirente',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Configurar bandeira JCB',
				'default'     => '0',
				'options'     => array(
					'0' => 'Bandeira Desabilitada',
					'1' => 'Cielo - Buy Page Loja',
					'2' => 'Cielo - Buy Page Cielo',
				)
			),
			'jcb_parcel_min' => array(
				'title'       => 'Parcela mínima',
				'type'        => 'text',
				'description' => 'Valor mínimo aceito para parcelamento com esta bandeira',
				'desc_tip'    => true,
			),
			'jcb_parcels' => array(
				'title'       => 'Máximo de parcelas',
				'type'        => 'select',
				'desc_tip'    => true,
				'description' => 'Selecione o máximo de parcelas aceitas por esta bandeira',
				'default'     => '0',
				'options'     => array(
					'1' => '1x',
					'2' => '2x',
					'3' => '3x',
					'4' => '4x',
					'5' => '5x',
					'6' => '6x',
					'7' => '7x',
					'8' => '8x',
					'9' => '9x',
					'10' => '10x',
					'11' => '11x',
					'12' => '12x',
				)
			),

		);

		$this->form_fields = $fields;
	}


	/**
	 * Process that use AZPay SDK
	 * @param  [type] $order_id [description]
	 * @return [type]           [description]
	 */
	public function process_payment($order_id) {

		global $woocommerce, $wpdb;


        /* reconrrencia inject */
        $rebill = array
        (
            'product'   => '',
            'active'    => false,
            'interval'  => '0'
        );

        /*
         * Se o primeiro item for item com recorrência
         * ele vai ativar as flags de recorrência
         * e sinalizar qual o tipo
         */
        $testOrder = new WC_Order($order_id);
        $produtos = $testOrder->get_items();
        $produtos = array_shift(array_slice($produtos, 0 , 1));
        $post_meta = get_post_meta($produtos['product_id']);

        if( isset($post_meta['wpcf-recorrencia']) && $post_meta['wpcf-recorrencia'] != '0' )
        {
            $rebill['active'] = true;
            $rebill['interval'] = ($post_meta['wpcf-recorrencia'] == 'mensal')?1:3;
            $rebill['product'] = $produtos['product_id'];
        }

//        0,mensal,trimestral

		try {

			$customer_order = new WC_Order($order_id);

			$flag = $_POST['azpaylte_cc_form_flag'];
			$name = $_POST['azpaylte_cc_form_name'];
			$number = $_POST['azpaylte_cc_form_number'];
			$parcels = $_POST['azpaylte_cc_form_parcel'];
			$validate = explode('/', $_POST['azpaylte_cc_form_validate']);
			$cvv = $_POST['azpaylte_cc_form_cvv'];

			$parcel_value = ceil($customer_order->order_total / $parcels);

            // Check value of parcel
			if ($parcel_value < $this->{$flag.'_parcel_min'})
				throw new Exception('Valor da parcela inválido.');

			// Check quantity of parcels
			if ($parcels > $this->{$flag.'_parcels'})
				throw new Exception('Quantidade inválida de parcelas.');

			$az_pay = new AZPay($this->merchant_id, $this->merchant_key);
			$az_pay->curl_timeout = 60;
			$az_pay->config_order['reference'] = $order_id;
			$az_pay->config_order['totalAmount'] = $customer_order->order_total;
			$az_pay->config_options['urlReturn'] = esc_url( home_url( '/azpay' ) );

			$az_pay->config_card_payments['amount'] = $customer_order->order_total;

			$acquirer = $flag . '_acquirer';
			$az_pay->config_card_payments['acquirer'] = $this->$acquirer;
			$az_pay->config_card_payments['method'] = ($parcels == '1') ? 1 : 2;
			$az_pay->config_card_payments['flag'] = $flag;
			$az_pay->config_card_payments['numberOfPayments'] = $parcels;
			$az_pay->config_card_payments['cardHolder'] = $name;
			$az_pay->config_card_payments['cardNumber'] = $number;
			$az_pay->config_card_payments['cardSecurityCode'] = $cvv;
			$az_pay->config_card_payments['cardExpirationDate'] = $validate[1].$validate[0];

			$az_pay->config_billing['customerIdentity'] = $customer_order->user_id;
			$az_pay->config_billing['name'] = $customer_order->billing_first_name . ' ' . $customer_order->billing_last_name;
			$az_pay->config_billing['address'] = $customer_order->billing_address_1;
			$az_pay->config_billing['city'] = $customer_order->billing_city;
			$az_pay->config_billing['state'] = $customer_order->billing_state;
			$az_pay->config_billing['postalCode'] = $customer_order->billing_postcode;
			$az_pay->config_billing['country'] = $customer_order->billing_country;
			$az_pay->config_billing['phone'] = $customer_order->billing_phone;
			$az_pay->config_billing['email'] = $customer_order->billing_email;

            //Recorrencia
            if($rebill['active'])
            {
                $time = date("Y-m-d");
                $az_pay->config_rebill['dateStart'] = $time;
                $az_pay->config_rebill['dateEnd'] = date("Y-m-d", strtotime( "+4 months", strtotime($time) ));

                $az_pay->config_rebill['period'] = '3';
                $az_pay->config_rebill['frequency'] = $rebill['interval'];
            }

			$payment_method_config = get_option( 'woocommerce_azpay_lite_creditcard_settings' );
			if (!isset($payment_method_config['clearsale']) || empty($payment_method_config['clearsale']) || $payment_method_config['clearsale'] == 'no') {

			} else {
				$az_pay->config_options['fraud'] = 'true';
				$az_pay->config_fraud_data['costumerIP'] = $_SERVER['REMOTE_ADDR'];
				$az_pay->config_fraud_data['name'] = $customer_order->billing_first_name . ' ' . $customer_order->billing_last_name;
				$az_pay->config_fraud_data['document'] = $customer_order->billing_cpf;
				$az_pay->config_fraud_data['phonePrefix'] = substr($customer_order->billing_phone, 0, 4);
				$az_pay->config_fraud_data['phoneNumber'] = $customer_order->billing_phone;
				$az_pay->config_fraud_data['address'] = $customer_order->billing_address_1;
				$az_pay->config_fraud_data['addressNumber'] = $customer_order->billing_number;
				$az_pay->config_fraud_data['address2'] = $customer_order->billing_address_2;
				$az_pay->config_fraud_data['city'] = $customer_order->billing_city;
				$az_pay->config_fraud_data['state'] = $customer_order->billing_state;
				$az_pay->config_fraud_data['postalCode'] = $customer_order->billing_postcode;
				$az_pay->config_fraud_data['email'] = $customer_order->billing_email;

				$order_items = $customer_order->get_items();

				$order_items_fraud = array();
				foreach ($order_items as $item) {
					$item = array(
						'productname' => $item['name'],
						'productqty' => $item['qty'],
						'productvalue' => $item['line_subtotal']
					);
					array_push($order_items_fraud, $item);
				}
				$az_pay->config_fraud_data['items'] = $order_items_fraud;

			}

			// XML to log
			$xml_log = clone $az_pay;		
			$xml_log->merchant['id'] = NULL;
			$xml_log->merchant['key'] = NULL;
			$xml_log->config_card_payments['cardNumber'] = preg_replace('/[0-9]/', 'X', $xml_log->config_card_payments['cardNumber']);
			$xml_log->config_card_payments['cardSecurityCode'] = preg_replace('/[0-9]/', 'X', $xml_log->config_card_payments['cardSecurityCode']);

			// Log XML
			$azpay_log = $wpdb->prefix.'azpay_log';
			$wpdb->insert( 
				$azpay_log, 
				array( 
					'datetime' => current_time('mysql'),
					'keylog' => 'SALE_XML',
					'orderid' => $order_id,
					'content' => $xml_log->sale()->getXml(),
				) 
			);

			if (!isset($payment_method_config['clearsale']) || empty($payment_method_config['clearsale']) || $payment_method_config['clearsale'] == 'no') {

                // Execute Sale
                if($rebill['active'])
                {
                    $az_pay->rebill()->execute();
                }
                else
                {
                    $az_pay->sale()->execute();
                }

				$gateway_response = $az_pay->response();

				if ($gateway_response == null)
					throw new Exception('Problemas ao obter resposta sobre pagamento.');

				if ($gateway_response->status != Config::$STATUS['APPROVED']) {
					$error = $az_pay->responseError();
					throw new Exception('Pagamento não Autorizado: ' . $error['error_message'], 1);
				}

				$customer_order->payment_complete();
				$customer_order->add_order_note("Pagamento relizado com sucesso. AZPay TID: {$gateway_response->transactionId}");
				
			} else {

				// Execute authorize
				$az_pay->authorize()->execute();

				$gateway_response = $az_pay->response();

				if ($gateway_response == null)
					throw new Exception('Problemas ao obter resposta sobre pagamento.');
				
				if ($gateway_response->status != Config::$STATUS['AUTHORIZED']) {
					throw new Exception(Config::$STATUS_MESSAGES[(int)$gateway_response->status]['title'], 1);
				}
				
				$customer_order->add_order_note("Pagamento autorizado pela operadora. AZPay TID: {$gateway_response->transactionId}");
				$customer_order->payment_complete();
				$customer_order->update_status('on-hold', 'Aguardando captura azpay');
			}

			$woocommerce->cart->empty_cart();

			// Log Response
			$wpdb->insert( 
				$azpay_log, 
				array( 
					'datetime' => current_time('mysql'),
					'keylog' => 'SALE_RESPONSE',
					'orderid' => $order_id,
					'content' => json_encode($gateway_response),
				) 
			);

			$response = array(
				'result'   => 'success',
				'redirect' => $url = $this->get_return_url($customer_order)
			);

		} catch (Exception $e) {
			
			$code = $az_pay->getCurlErrorCode();

		 	// cURL error (Timeout)
			if ($e instanceof AZPay_Curl_Exception && $code == 28) {

				$customer_order->update_status('processing', 'Aguardando confirmação de pagamento');
				$woocommerce->cart->empty_cart();

				$response = array(
					'result'   => 'success',
					'redirect' => $url = $this->get_return_url($customer_order)
				);

			} else {

				// Error = 0 from SDK
				if ($e->getCode() == 0) {
					$error = $az_pay->responseError();
					$message = $error['error_message'] . ' (' . $error['error_code'] . ' - ' . $error['error_moreInfo'] . ')';
				} else {
					$message = $e->getMessage();
				}
				
				$this->add_error($message);

				// Log Error
				$wpdb->insert( 
					$azpay_log, 
					array( 
						'datetime' => current_time('mysql'),
						'keylog' => 'SALE_ERROR',
						'orderid' => $order_id,
						'content' => json_encode($error),
					) 
				);

				$response = array(
					'result'   => 'fail',
					'redirect' => ''
				);
			}
		}

		return $response;
	}


	public function currency_not_supported_message() {
		echo '<div class="error"><p><strong>AZPay Lite - Cartão de Crédito</strong>: Moeda não aceita</p></div>';
	}


	/**
	 * Save the configuration of Creditcards
	 * @return [type] [description]
	 */
	public function save_creditcard_config() {

		if (isset($_POST['woocommerce_azpay_lite_creditcard']))
			update_option('woocommerce_azpay_lite_creditcard', json_encode($this->filterData($_POST['woocommerce_azpay_lite_creditcard'])));
	}


	/**
	 * Payment fields.
	 *
	 * @return string
	 */
	public function payment_fields() {
		global $woocommerce;

		$cart_total = 0;
		if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.1', '>=')) {
			$order_id = absint(get_query_var('order-pay'));
		} else {
			$order_id = isset($_GET['order_id']) ? absint($_GET['order_id']) : 0;
		}

		if ($order_id > 0) {
			$order      = new WC_Order($order_id);
			$cart_total = (float) $order->get_total();
		} elseif ($woocommerce->cart->total > 0) {
			$cart_total = (float) $woocommerce->cart->total;
		}

		echo $this->description;

		$html_path = apply_filters('wc_azpay_creditcard_form', plugin_dir_path(__FILE__) . 'views/html-form-creditcard.php');

		if (file_exists($html_path))
			include_once($html_path);
	}


	/**
	 * Validate cart total to show parcels
	 * @param  [type] $cart_total [description]
	 * @param  [type] $flag       [description]
	 * @return [type]             [description]
	 */
	public function validate_parcel($cart_total, $flag) {

		$flag = $flag . '_parcel_min';

		if ($cart_total >= $this->$flag)
			return true;

		return false;
	}


	/**
	 * Return the quantity of parcels that are accepted
	 * @param  [type] $flag [description]
	 * @return [type]       [description]
	 */
	public function parcel_qnt($cart_total, $flag) {

		// Max os parcels accepted by this flag
		$flag_parcels = $flag . '_parcels';
		$flag_parcel_min = $flag . '_parcel_min';

		$parcels = ceil($cart_total / $this->$flag_parcel_min);

		if ($parcels > $this->$flag_parcels)
			return $this->$flag_parcels;

		return $parcels;
	}


	/**
	 * Return a select field with accepted parcels
	 * @param  [type] $cart_total [description]
	 * @param  [type] $flag       [description]
	 * @return [type]             [description]
	 */
	public function get_select_parcel_html($cart_total, $flag) {

		// Max os parcels accepted by this flag
		$parcels = $flag . '_parcels';
		$parcel_min = $flag . '_parcel_min';
		$max_parcels = $this->$flag;

		$html = '<select name="azpaylte_cc_form_parcel" id="azpaylte_cc_form_parcel">';

        for($i = 1; $i <= $this->$parcels ; $i++) {

        	$value_parcel = $cart_total / $i;
        	if ($value_parcel >= $this->$parcel_min) {
        		$html .= '<option value="'.$i.'">'.$i.'x</option>';
        	}
        }

        $html = '</select>';

        return $html;
	}


	/**
	 * Add an error
	 * @param [type] $message [description]
	 */
	public function add_error($message) {
		global $woocommerce;

		if (function_exists('wc_add_notice')) {
			wc_add_notice($message, 'error');
		} else {
			$woocommerce->add_error($message);
		}
	}

}
