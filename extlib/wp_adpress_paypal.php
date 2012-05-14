<?php
/**
 * PayPal ExpressCheckOut for WordPress
 * 
 * This code is not licensed. Feel free to use it in your own open source and 
 * commercial projects. The code is provided "AS IS" without any warranty or
 * conditions of any kind.
 * 
 * @author Abid Omar
 */
class wp_adpress_paypal {

    /**
     * Gateway parameters
     *
     * @var array 
     */
    private $gateway;

    /**
     * PayPal API servers
     * @var string 
     */
    private $server = 'https://api-3t.paypal.com';

    /**
     * PayPal Payment processing URL 
     * @var string 
     */
    private $redirect_url = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';

    /**
     * Create a new instance of the PayPal class.
     * 
     * @param array $param
     * @param boolean $test_mode set to True for Sandbox mode
     */
    function __construct ( $param, $test_mode = false ) {
        /*
         * Set the gateway array variables
         */
        $this->gateway = array(
            'USER' => $param['username'],
            'PWD' => $param['password'],
            'SIGNATURE' => $param['signature'],
            'PAYMENTREQUEST_0_PAYMENTACTION' => $param['payment_action'],
            'PAYMENTREQUEST_0_AMT' => $param['payment_amount'],
            'PAYMENTREQUEST_0_CURRENCYCODE' => $param['currency'],    /* @Popshop fixed bug */
            'RETURNURL' => $param['return_url'],
            'CANCELURL' => $param['cancel_url'],
            'VERSION' => $param['version'],
            'NOSHIPPING' => 1,
            'ALLOWNOTE' => 1
        );
        /*
         * Change the server and redirect url if we are in a test mode
         */
        if ( $test_mode ) {
            $this->server = 'https://api-3t.sandbox.paypal.com/nvp';
            $this->redirect_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=';
        }
    }

    /**
     * Generate the redirect URL that will ask the user for payment permission
     * 
     * @return string Redirect URL 
     */
    public function doExpressCheckout () {
        $body = $this->gateway;
        $body['METHOD'] = 'SetExpressCheckout';
        $request = array(
            'method' => 'POST',
            'body' => $body,
            'timeout' => 60,
            'sslverify' => false
        );
        $response = wp_remote_post($this->server, $request);
        if ( is_wp_error($response) ) {
            return false;
        }
        parse_str(urldecode($response['body']), $response);
        if ( strtolower($response['ACK']) === 'success' ) {
            return ($this->redirect_url . $response['TOKEN']);
        } else {
            return false;
        }
    }

    /**
     * Process the payment.
     * 
     * The function returns true if the user completed the payment, and false in the
     * other case.
     * 
     * @param string $token
     * @param string $payer_id
     * @return boolean 
     */
    public function processPayment ( $token, $payer_id ) {
        $body = $this->gateway;
        $body['METHOD'] = 'DoExpressCheckoutPayment';
        $body['PAYERID'] = $payer_id;
        $body['TOKEN'] = $token;
        $request = array(
            'method' => 'POST',
            'body' => $body,
            'timeout' => 60,
            'sslverify' => false
        );
        $response = wp_remote_post($this->server, $request);
        if ( is_wp_error($response) ) {
            return false;
        }
        parse_str(urldecode($response['body']), $response);
        // @Popshop Added pending status as success.
        if ( strtolower($response['ACK']) === 'success' && in_array(strtolower($response['PAYMENTINFO_0_PAYMENTSTATUS']), 
                                                                    array('completed', 'pending')) ) {
            return true;
        } else {
            return false;
        }
    }

}
