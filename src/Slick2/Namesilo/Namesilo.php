<?php

/**
 * Name: Namesilo
 * 
 * Author: Carey Dayrit
 *          carey.dayrit@gmail.com 
 * 
 * NOTE: This is still in beta not all methods are tested properly, use it at your own risk.
 */
 
namespace Slick2\Namesilo

class Namesilo {

    protected $url;
    protected $key;
    protected $debug;
    public $error;

    public function __construct($url, $key, $debug = false) {
        
        
          $this->url = $url;
          $this->key = $key;
        
    }

    /** Register Domain * */
    public function register_domain($domain, $contact_id, $years = 1, $private = 1, $auto_renew = 0) {
        $result = $this->request('registerDomain', [
            ['domain', $domain],
            ['years', $years],
            ['private', $private],
            ['auto_renew', $auto_renew],
            ['contact_id', $contact_id],
        ]);

        if (!$this->request_successp($result['reply'])) {
            return false;
        }
        return $result['reply'];
    }

    public function register_domain_drop($domain, $years = 1, $private = 1, $auto_renew = 0) {
        $result = $this->request('registerDomainDrop', [
            ['domain', $domain],
            ['years', $years],
            ['private', $private],
            ['auto_renew', $auto_renew]
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }
        return $result['reply'];
    }

    public function renew_domain($domain, $years = 1, $payment_id = NULL, $coupon = NULL) {
        $result = $this->request('renewDomain', [
            ['domain', $domain],
            ['years', $years],
            ['payment_id', $payment_id],
            ['coupon', $coupon]
        ]);
        if (!$this->request_successp($result)) {
            return false;
        }
        return $result['reply'];
    }

    public function transfer_domain($domain, $payment_id = NULL, $auth = NULL, $private = NULL, $auto_renew = 0, $portfolio = NULL, $coupon = NULL) {
        $result = $this->request('transferDomain', [
            ['domain', $domain],
            ['auth', $auth],
            ['private', $private],
            ['auto_renew', $auto_renew],
            ['portfolio', $portfolio],
            ['coupon', $coupon]
        ]);
        if (!$this->request_successp($result)) {
            return false;
        }
        return $result['reply'];
    }

    public function check_transfer_status($domain) {
        $result = $this->request('checkTransferStatus', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }

        return $result['reply'];
    }

    public function check_register_availability($domain) {
        $result = $this->request('checkRegisterAvailability', [['domains', $domain]]);
        if ($this->request_successp($result) && isset($result['reply']['available']))
            return 'available';
        if ($this->request_successp($result) && isset($result['reply']['invalid']))
            return 'invalid';
        if ($this->request_successp($result) && isset($result['reply']['unavailable']))
            return 'unavailable';
        return false;
    }

    public function check_transfer_availability($domain) {
        $result = $this->request('checkTransferAvailability', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }

        return $result['reply'];
    }

    public function list_domains($portfolio = null) {
        $result = $this->request('listDomains', [
            ['portfolio', $portfolio]
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }

        return $result['reply'];
    }

    public function get_domain_info($domain = null) {
        $result = $this->request('getDomainInfo', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result)) {
            return false;
        }

        return $result['reply'];
    }

    /** Contacts * */
    public function contact_list() {
        $result = $this->request('contactList');
        if (!$this->request_successp($result))
            return false;
        return $result['reply']['contact'];
    }

    public function contact_add($fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph) {
        $result = $this->request('contactAdd', [
            ['fn', $fn], // first name
            ['ln', $ln], // last name
            ['ad', $ad], // address
            ['cy', $cy], // city
            ['st', $st], // state
            ['zp', $zp], // zip
            ['ct', $ct], // country
            ['em', $em], // email
            ['ph', $ph], // phone number
        ]);
        if ($this->request_successp($result))
            return $result['reply']['contact_id'];
        return false;
    }

    public function contact_update($contact_id, $fn, $ln, $ad, $cy, $st, $zp, $ct, $em, $ph) {
        $result = $this->request('contactUpdate', [
            ['contact_id', $contact_id],
            ['fn', $fn], // first name
            ['ln', $ln], // last name
            ['ad', $ad], // address
            ['cy', $cy], // city
            ['st', $st], // state
            ['zp', $zp], // zip
            ['ct', $ct], // country
            ['em', $em], // email 
            ['ph', $ph], // phone number			
        ]);
        if ($this->request_successp($result))
            return true;
        return false;
    }

    public function contact_delete($contact_id) {
        $result = $this->request('contactDelete', [
            ['contact_id', $contact_id]
        ]);
        if ($this->request_successp($result))
            return true;
        return false;
    }

    public function contact_domain_associate($domain) {
        $result = $this->request('contactDomainAssociate', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function dns_list_records($domain) {
        $result = $this->request('dnsListRecords', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function dns_add_record($domain, $type, $host, $value, $distance = '', $ttl = '') {
        $result = $this->request('dnsAddRecord', [
            ['domain', $domain],
            ['rrtype', $type],
            ['rrhost', $host],
            ['rrvalue', $value],
            ['rrdistance', $distance],
            ['rrttl', $ttl],
        ]);
        if ($this->request_successp($result))
            return true;
        else
            return false;
    }

    public function dns_update_record($domain, $rrid, $rrhost, $rrvalue) {
        $result = $this->request('dnsUpdateRecord', [
            ['domain', $domain],
            ['rrid', $rrid],
            ['rrhost', $rrvalue]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function dns_delete_record($domain, $rrid = null) {
        $request = $this->request('dnsDeleteRecord', [
            ['domain', $domain],
            ['rrid', $rrid]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function change_name_servers($domain, $ns1, $ns2) {
        $request = $this->request('changeNameServers', [
            ['domain', $domain],
            ['ns1', $ns1],
            ['ns2', $ns2]
        ]);
        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    /** Portfolio * */
    public function portfolio_list() {
        $result = $this->request('portfolioList');
        if (!$this->request_successp($result))
            return false;
        return $result['reply']['portfolios'];
    }

    public function portfolio_add($portfolio) {
        $result = $this->request('portfolioAdd', [
            ['portfolio', $portfolio]
        ]);

        if ($this->request_successp($result))
            return true;
        return false;
    }

    public function portfolio_delete($portfolio) {
        $result = $this->request('portfolioDelete', [
            ['portfolio', $portfolio]
        ]);

        if ($this->request_successp($result))
            return true;
        return false;
    }

    public function portfolio_domain_associate($portfolio, $domains) {
        $result = $this->request('portfolioDomainAssociate', [
            ['portfolio', $portfolio],
            ['domains', $domains]
        ]);

        if ($this->request_successp($result))
            return true;
        return false;
    }

    public function list_registered_name_servers($domain) {
        $result = $this->request('listRegisteredNameServers', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    /** Note: this should be revised * */
    public function add_registered_name_server($domain, $new_host, $ip1, $ip2) {
        $result = $this->request('addRegisteredNameServer', [
            ['domain', $domain],
            ['new_host', $new_host],
            ['ip1', $ip1],
            ['ip2', $ip2]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function modify_registered_name_server($domain, $current_host, $new_host, $ip1, $ip2) {
        $result = $this->request('modifyRegisteredNameServer', [
            ['domain', $domain],
            ['current_host', $current_host],
            ['new_host', $new_host],
            ['ip1', $ip1],
            ['ip2', $ip2]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function delete_registered_name_server($domain, $current_host) {
        $result = $this->request('deleteRegisteredNameServer', [
            ['domain', $domain],
            ['current_host', $current_host]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function add_privacy($domain) {
        $result = $this->request('addPrivacy', [
            ['domain', $domain],
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function remove_privacy($domain) {
        $result = $this->request('removePrivacy', [
            ['domain', $domain],
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function add_auto_renewal($domain) {
        $result = $this->request('addAutoRenewal', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function remove_auto_renewal($domain) {
        $result = $this->request('removeAutoRenewal', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function retrieve_auth_code($domain) {
        $result = $this->request('retrieveAuthCode', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function domain_forward($domain, $protocol, $address, $method) {
        $result = $this->request('domainForward', [
            ['domain', $domain],
            ['protocol', $protocol],
            ['address', $address],
            ['method', $method]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function domain_forward_sub_domain($domain, $sub_domain, $protocol, $address, $method) {
        $result = $this->request('domainForward', [
            ['domain', $domain],
            ['sub_domain', $sub_domain],
            ['protocol', $protocol],
            ['address', $address],
            ['method', $method]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function domain_forward_sub_domain_delete($domain, $sub_domain) {
        $result = $this->request('domainForward', [
            ['domain', $domain],
            ['sub_domain', $sub_domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function domain_lock($domain) {
        $result = $this->request('domainLock', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function domain_unlock($domain) {
        $result = $this->request('domainUnlock', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function list_email_forwards($domain) {
        $result = $this->request('listEmailForwards', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function configure_email_forward($domain, $email, $forward1) {
        $result = $this->request('configureEmailForward', [
            ['domain', $domain],
            ['email', $email],
            ['forward1', $forward1]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function delete_email_forward($domain, $email) {
        $result = $this->request('deleteEmailForward', [
            ['domain', $domain],
            ['email', $email]
        ]);
        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function email_verification($email) {
        $result = $this->request('emailVerification', [
            ['email', $email]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function get_account_balance() {
        $result = $this->request('getAccountBalance');

        if (!$this->request_successp($result))
            return false;

        return $result['reply']['balance'];
    }

    public function add_account_funds($amount, $payment_id) {
        $result = $this->request('addAccountFunds', [
            ['amount', $amount],
            ['payment_id', $payment_id]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply']['new_balance'];
    }

    public function marketplace_active_sales_overview() {
        $result = $this->request('marketplaceActiveSalesOverview');

        if (!$this->request_successp($result))
            return false;

        return $result['reply']['sale_details'];
    }

    public function marketplace_add_or_mofify_sale($domain, $action, $sale_type) {
        $result = $this->request('marketplaceAddOrModifySale', [
            ['domain', $domain],
            ['action', $action],
            ['sale_type', $sale_type]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply']['detail'];
    }

    public function marketplace_landing_page_update($domain) {
        $result = $this->request('marketplaceAddOrModifySale', [
            ['domain', $domain]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply']['detail'];
    }

    public function get_prices($retail_prices, $registration_domains) {
        $result = $this->request('getPrices', [
            ['retail_prices', $retail_prices],
            ['registration_domains', $registration_domains]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function list_orders() {
        $result = $this->request('listOrders');

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    public function order_details($order_number) {
        $result = $this->request('orderDetails', [
            ['order_number', $order_number]
        ]);

        if (!$this->request_successp($result))
            return false;

        return $result['reply'];
    }

    private function request($command, $options = '') {

        $created_options = '';
        if (!empty($options)) {
            foreach ($options as $pair) {
                $created_options .= '&';
                $created_options .= $pair[0];
                $created_options .= '=';
                $created_options .= urlencode($pair[1]);
            }
        }
        $command_ready = $this->url . $command . '?version=1&type=xml&key=' . $this->key . $created_options;
        $str = $this->_http_get($command_ready);
        $result = $this->xml_to_arr($str);
        if ($this->debug) {
            echo '<pre>';
            print_r($result);
            echo '</pre>';
        }
        if (!$this->request_successp($result)) {
            $this->error = $result['reply']['detail'];
        }
        if ($result['reply']['code'] == 301 || $result['reply']['code'] == 302) {
            $this->error = $result['reply']['detail'];
        }
        return $result;
    }

    private function _http_get($url) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    private function xml_to_arr($str) {
        $str = trim($str);
        $xml = simplexml_load_string($str);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        return $array;
    }

    private function request_successp($arr) {
        if ($arr['reply']['code'] == 300 || $arr['reply']['code'] == 301 || $arr['reply']['code'] == 302)
            return true;
        else
            return false;
    }

}
