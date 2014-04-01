<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_send_email
{
  private $debug = FALSE;
  private $error = array();
  
  function __construct($options = array())
  {
    $this->ci =& get_instance();

    $this->ci->config->item('tank_auth');
    $this->ci->load->library('email', $this->ci->config->item('email'));
  }
  
  /**
   * Get error message.
   * Can be invoked after any failed operation such as login or register.
   *
   * @return  string
   */
  function get_error_message()
  {
    return $this->error;
  }
  
  /**
   * Send email message of given type (activate, forgot_password, etc.)
   *
   * @param  string
   * @param  string
   * @param  array
   * @return  void
   */
  function general($subject, $email_to, $type, &$data)
  {
    $site_name = $this->ci->config->item('website_name', 'tank_auth');
    $data['site_name'] = $site_name;
    
    $message = $this->ci->load->view('email/'.$type.'-html', $data, TRUE);
    $alt_message = $this->ci->load->view('email/'.$type.'-txt', $data, TRUE);

    $from = $this->ci->config->item('webmaster_email', 'tank_auth');
    $from_name = (!empty($reply_to_name) ? $reply_to_name.' via ' : '').$site_name;
    
    $this->ci->email->from($from, $from_name);
    $this->ci->email->to($email_to);

    //$this->ci->email->subject(sprintf($this->lang->line('auth_subject_'.$type), $site_name));
    $this->ci->email->subject($subject);
    
    $this->ci->email->message($message);
    $this->ci->email->set_alt_message($alt_message);
    
    $this->ci->email->send();

    if ($this->debug)
    {
      var_dump($email_to, $subject);
      echo($message); 
      var_dump($alt_message);
      die();

      //echo $this->ci->email->print_debugger(); die();
    }
  }
}