<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// https://github.com/bshaffer/oauth2-server-demo/blob/master/src/Demo/Curl.php

class Lib_cURL_client
{
  private $options;
  private $debug = FALSE;
  
  public function __construct($options = array())
  {
    $this->ci =& get_instance();
    $this->ci->load->config('curl', TRUE);
    $basic_options = $this->ci->config->item('curl_basic', 'curl');
    
    $this->options = array_replace($basic_options, $options);
  }
  
  public function request($url, $params = array(), $http_method = 'GET', $options = array())
  {
    $options = array_replace($this->options, $options);
    
    switch ($http_method)
    {
    case 'POST':
      $options[CURLOPT_POST] = TRUE;
      break;
    case 'PUT':
      $options[CURLOPT_POST] = TRUE; // This is so cURL doesn't strip CURLOPT_POSTFIELDS
      $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
      break;
    case 'PATCH':
      $options[CURLOPT_POST] = TRUE; // This is so cURL doesn't strip CURLOPT_POSTFIELDS
      $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
      break;
    case 'DELETE':
      $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      break;
    }
    
    if (!empty($params['query']))
    {
      $query_string = utf8_encode(http_build_query($params['query']));
      $url .= '?' . $query_string;
    }
    
    if (!empty($params['post_fields']))
    {
      $options[CURLOPT_POSTFIELDS] = $params['post_fields'];
    }
    else
    {
      $params['header']['Content-Length'] = 0;
    }
    
    //$params['header']['Content-Length'] = strlen($params['post_fields']);
    
    $header = array();
    if (!empty($params['header'])) foreach ($params['header'] as $key => $value) $header[] = $key.': '.$value;
    
    $options[CURLOPT_URL] = $url;
    $options[CURLOPT_HTTPHEADER] = $header;
    
    if (ini_get('open_basedir') == '' && ini_get('safe_mode') != 'On')
    {
      $options[CURLOPT_FOLLOWLOCATION] = TRUE;
    }
    
    $this->_debug($http_method, $url, $params); //die();
    
    return $this->do_curl_call($options);
  }
  
  private function do_curl_call($options = array())
  {
    $curl = curl_init();
    
    curl_setopt_array($curl, $options);
    
    $response = curl_exec($curl);
    $header = curl_getinfo($curl);
    $errorNumber = curl_errno($curl);
    $errorMessage = curl_error($curl);
    
    curl_close($curl);
    
    //var_dump($options);
    //var_dump(compact('response', 'header', 'errorNumber', 'errorMessage'));
    
    return $response;
  }
  
  private function _debug($http_method, $url, $params)
  {
    if($this->debug)
    {
      var_dump('curl -'.$http_method.' -'.$url);
      if (!empty($params['header'])) var_dump($params['header']);
      if (!empty($params['post_fields'])) var_dump($params['post_fields']);
    }
  }
}