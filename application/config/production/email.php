<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'tcp://smtpout.asia.secureserver.net';
$config['smtp_port'] = 80;
$config['smtp_user'] = 'noreply@stringyourstory.com';
$config['smtp_pass'] = 'noreply@stringyourstory.com';
$config['mailtype'] = 'html';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */