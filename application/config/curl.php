<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| cURL LOCAL Settings
| -------------------------------------------------------------------------
|
*/
$config['curl_basic'] = array(
  //CURLOPT_PORT            => '80',
  //CURLOPT_USERAGENT       => 'PHP-curl-client (https://github.com/bshaffer/oauth2-server-demo)',
  
  CURLOPT_PROXY           => 'netmon.iitb.ac.in:80',
  CURLOPT_PROXYUSERPWD    => 'shubhajit:!maniac',
  //CURLOPT_PROXYPORT       => '80',
  //CURLOPT_PROXYAUTH       => CURLAUTH_NTLM,
  
  //CURLOPT_SSLVERSION      => 3,
  
  CURLOPT_TIMEOUT         => 60,
  CURLOPT_SSL_VERIFYPEER  => TRUE,
  CURLOPT_RETURNTRANSFER  => TRUE,
  
  CURLOPT_HTTPAUTH        => CURLAUTH_ANY,
  //CURLOPT_FOLLOWLOCATION  => TRUE,
);

/* End of file curl.php */
/* Location: ./application/config/oauth.php */