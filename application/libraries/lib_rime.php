<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lib_rime
{
  function __construct($user_id = 0)
  {
    $this->ci =& get_instance();
    
    $this->oa_config['consumer_key']          = 'jodo';
    $this->oa_config['consumer_secret']       = 'jodo_secret';
    $this->oa_config['authorize_url']         = 'http://rimebeta.com/api/client/authenticate';
    $this->oa_config['access_token_url']      = 'http://rimebeta.com/api/client/access_token';    

    $this->oa_config['redirect_url']          = base_url('auth/callback');

    $this->ci->load->library('lib_curl_client');
  }
  
  function get_request_token()
  {
    // construct authorize url
    $authorize_url = $this->oa_config['authorize_url'].'?';
    
    $authorize_url .= '&client_id='.$this->oa_config['consumer_key'];
    $authorize_url .= '&response_type=code';
    $authorize_url .= '&redirect_uri='.$this->oa_config['redirect_url'];
    
    redirect($authorize_url);
  }

  function get_access_token()
  {
    if (empty($_GET['code'])) // request denied
    {
      $this->error = array('message' => 'request denied');
      return NULL;
    }
    
    $oauth_token = $_GET['code'];
    
    $query = array(
      'code'          => $oauth_token,
      'client_id'     => $this->oa_config['consumer_key'],
      'client_secret' => $this->oa_config['consumer_secret'],
      'redirect_uri'  => $this->oa_config['redirect_url'],
      'grant_type'    => 'authorization_code',
    );
    
    $params['query']  = $query;
    
    $json_response = $this->ci->lib_curl_client->request($this->oa_config['access_token_url'], $params, 'GET');
    
    $token = json_decode($json_response, TRUE);
    
    if (empty($token['access_token']))
    {
      $this->error = array('message' => 'access token not found');
      return NULL;
    }

    return $token;
  }
  
  function get_user_profile($access_token)
  {
    $params['query']['access_token'] = $access_token;
    
    $json_response = $this->ci->lib_curl_client->request('http://rimebeta.com/api/user/get_profile', $params, 'GET');    

    $profile_data = json_decode($json_response, TRUE);

    if (empty($profile_data))
    {
      $this->error = array('message' => 'access token error');
      return NULL;
    }

    return $profile_data;
  }
}