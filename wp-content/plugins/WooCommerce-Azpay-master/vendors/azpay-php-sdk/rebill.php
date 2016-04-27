<?php

/**
 * Class Rebill
 *
 * Provavelmente nunca vou usar isso aqui
 */
class Rebill
{
    /**
     * Responsável pelos node ORDER
     *
     * @var array
     */
    private $order = array
    (
        'reference' => '',
        'dataAmount' => '',
        'period' => '',
        'frequency' => '',
        'dateStart' => '',
        'dateEnd' => ''
    );

    /**
     * Responável pelo node paymentCreditCard
     *
     * @var array
     */
    private $paymentCreditCard = array
    (
        'acquirer' => '',
        'amount' => '',
        'currency' => '',
        'country' => '',
        'numberOfPayments' => '',
        'groupNumber' => '',
        'flag' => '',
        'cardHolder' => '',
        'cardNumber' => '',
        'cardSecurityCode' => '',
        'cardExpirationDate' => '',
        'saveCreditCard' => '',
        'generateToken' => '',
        'departureTax' => ''
    );

    /**
     * Responável pelo node billing
     *
     * @var array
     */
    private $billing = array
    (
        'customerIdentity' => '',
        'name' => '',
        'address' => '',
        'address2' => '',
        'city' => '',
        'state' => '',
        'postalCode' => '',
        'country' => '',
        'phone' => '',
        'email' => ''
    );

    /**
     * URL de retorno que receberá um POST depois de cada alteração de status da transação.
     *
     * @var
     */
    private $urlReturn;

    /**
     * Campos para usos diversos
     *
     * @var array
     */
    private $customField = array();
}