<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_oauth_client
{
  private $oauth_version = '1.0';
  private $consumer_key = '';
  private $consumer_secret = '';
  
  private $redirect_url = '';
  
  private $oauth_token = '';
  private $token_secret = '';
  
  function __construct()
  {
    $this->ci =& get_instance();
    $this->ci->load->library('lib_curl_client');
  }
  
  function set_options($params = array())
  {
    $this->oauth_version    = $params['oauth_version'];
    $this->consumer_key     = $params['consumer_key'];
    $this->consumer_secret  = $params['consumer_secret'];
    
    $this->oauth_signature_method = !empty($params['oauth_signature_method']) ? $params['oauth_signature_method'] : 'HMAC-SHA1';
    
    $this->redirect_url     = $params['redirect_url'];
  }
  
  private function _build_base_string($baseURI, $method, $params)
  {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value)
    {
      $r[] = $key.'='.urlencode($value);
    }
    return $method.'&'.urlencode($baseURI).'&'.urlencode(implode('&', $r)); 
  }
  
  private function _build_composite_key()
  {
    return urlencode($this->consumer_secret).'&'.urlencode($this->token_secret);
  }
  
  private function buildSignature($baseURI, $method, $params)
  {
    if ($this->oauth_signature_method == 'PLAINTEXT')
    {
      $oauth_signature = $this->consumer_secret.'&'.$this->token_secret;
    }
    else // if ($this->oauth_signature_method == 'HMAC-SHA1')
    {
      $base_info = $this->_build_base_string($baseURI, $method, $params);
      $composite_key = $this->_build_composite_key();
      $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, TRUE));
    }
    
    //var_dump($oauth_signature); die();
    
    return $oauth_signature;
  }
  
  // OAuth 1.0 only
  function get_request_token($request_token_url, $redirect_url = '')
  {
    if ($this->oauth_version == '1.0')
    {
      if (!empty($redirect_url)) $this->redirect_url = $redirect_url;
      
      $params['query'] = array(
        'oauth_version'           => '1.0',
        'oauth_nonce'             => time(),
        'oauth_timestamp'         => time(),
        'oauth_consumer_key'      => $this->consumer_key,
        'oauth_signature_method'  => $this->oauth_signature_method,
        'oauth_callback'          => $this->redirect_url,
      );
      
      $params['query']['oauth_signature'] = $this->buildSignature($request_token_url, 'GET', $params['query']);
      
      $response = $this->ci->lib_curl_client->request($request_token_url, $params);
      parse_str($response, $response_array);
      
      return $response_array;
    }
  }
  
  // OAuth 1.0 = set_token ( oauth_token, token_secret )
  // OAuth 2.0 = set_token ( oauth_token )
  function set_token($oauth_token, $token_secret = '')
  {
    $this->oauth_token = $oauth_token;
    $this->token_secret = $token_secret;
  }
  
  function get_access_token($access_token_url, $http_method = 'POST')
  {
    switch($this->oauth_version)
    {
    case '1.0':
      $params['query'] = array(
        'oauth_version'           => '1.0',
        'oauth_nonce'             => time(),
        'oauth_timestamp'         => time(),
        'oauth_consumer_key'      => $this->consumer_key,
        'oauth_signature_method'  => $this->oauth_signature_method,
        'oauth_token'             => $this->oauth_token,
      );
      
      if (isset($_GET['oauth_verifier']))
        $params['query']['oauth_verifier'] = $_GET['oauth_verifier'];
      
      $params['query']['oauth_signature'] = $this->buildSignature($access_token_url, 'GET', $params['query']);
      
      $response = $this->ci->lib_curl_client->request($access_token_url, $params);
      parse_str($response, $response_array);
      
      return $response_array;
      
    case '2.0':
      $query = array(
        'code'          => $this->oauth_token,
        'client_id'     => $this->consumer_key,
        'client_secret' => $this->consumer_secret,
        'redirect_uri'  => $this->redirect_url,
        'grant_type'    => 'authorization_code',
      );
      
      if ($http_method == 'POST') $params['post_fields']  = $query;
      else                        $params['query']        = $query;
      
      $json_response = $this->ci->lib_curl_client->request($access_token_url, $params, $http_method);
      
      $response_array = json_decode($json_response, TRUE);
      
      // facebook shits: returns string response
      if (strpos($access_token_url, 'graph.facebook.com') !== FALSE)
      {
        parse_str($json_response, $response_array);
      }
      
      return $response_array;
    }
  }
  
  // OAuth 2.0 only
  function refresh_access_token($refresh_token, $access_token_url, $http_method = 'POST')
  {
    if ($this->oauth_version == '2.0')
    {
      $query = array(
        'client_id'     => $this->consumer_key,
        'client_secret' => $this->consumer_secret,
        'refresh_token' => $refresh_token,
        'grant_type'    => 'refresh_token',
      );
      
      // facebook shits:
      if (strpos($access_token_url, 'graph.facebook.com') !== FALSE)
      {
        $query['grant_type'] = 'fb_exchange_token';
        $query['fb_exchange_token'] = $refresh_token;
      }
      
      if ($http_method == 'POST') $params['post_fields']  = $query;
      else                        $params['query']        = $query;
      
      $json_response = $this->ci->lib_curl_client->request($access_token_url, $params, $http_method);
      
      $response_array = json_decode($json_response, TRUE);
      
      // facebook shits: returns string response
      if (strpos($access_token_url, 'graph.facebook.com') !== FALSE)
      {
        parse_str($json_response, $response_array);
      }
      
      return $response_array;
    }
  }
  
  function fetch($url, $params = array(), $http_method = 'GET', $options = array())
  {
    switch($this->oauth_version)
    {
    case '1.0':
      $params['query']['oauth_version'          ] = '1.0';
      $params['query']['oauth_nonce'            ] = time().base_convert(rand(), 10, 36).base_convert(rand(), 10, 36);
      $params['query']['oauth_timestamp'        ] = time();
      $params['query']['oauth_consumer_key'     ] = $this->consumer_key;
      $params['query']['oauth_signature_method' ] = $this->oauth_signature_method;
      $params['query']['oauth_token'            ] = $this->oauth_token;
      
      $params['query']['oauth_signature'        ] = $this->buildSignature($url, $http_method, $params['query']);
      
      $json_response = $this->ci->lib_curl_client->request($url, $params, $http_method, $options);
      //$response_array = json_decode($json_response, TRUE);
      
      return $json_response;
      
    case '2.0':
      if (strpos($url, 'api.foursquare.com') !== FALSE)
      {
        // foursquare shits:
        $params['query']['oauth_token'] = $this->oauth_token;
      }
      else
      {
        $params['query']['access_token'] = $this->oauth_token;
        //$params['header']['Authorization'] = 'Bearer '.$this->oauth_token;
      }
      
      $json_response = $this->ci->lib_curl_client->request($url, $params, $http_method, $options);
      //$response_array = json_decode($json_response, TRUE);
      
      return $json_response;
    }
  }

  function get_auth_file($url, $mime_type, $options = array())
  {
    header('Content-Type: '.$mime_type);
    
    $options[CURLOPT_RETURNTRANSFER] = FALSE;
    $options[CURLOPT_TIMEOUT] = 0;
    
    switch($this->oauth_version)
    {
    case '1.0':
      $params['header']['Authorization'] = 'OAuth oauth_version="1.0", oauth_consumer_key="'.$this->consumer_key.'", oauth_signature_method="'.$this->oauth_signature_method.'", oauth_token="'.$this->oauth_token.'", oauth_signature="'.$this->buildSignature($url, 'GET', array()).'"';
      
      $this->ci->lib_curl_client->request($url, $params, 'GET', $options); exit();
      break;
      
    case '2.0':
      $params['header']['Authorization'] = 'Bearer '.$this->oauth_token;
      
      $this->ci->lib_curl_client->request($url, $params, 'GET', $options); exit();
      break;
    }
  }
}