<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_user_profile
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    //$this->ci->load->config('user_profile', TRUE);
    
    $this->ci->load->database();
    $this->ci->load->model('model_profile');
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
  
  function get_user_profile_by_id($user_id)
  {
    $profile_data = $this->ci->model_profile->get_user_profile_by_id($user_id);
    if (empty($profile_data))
    {
      $this->error = array('message' => 'invalid user id');
      return NULL;
    }
    
    return $profile_data;
  }
  
  function get_user_profile_by_username($username)
  {
    $profile_data = $this->ci->model_profile->get_user_profile_by_username($username);
    if (empty($profile_data))
    {
      $this->error = array('message' => 'invalid username');
      return NULL;
    }
    
    $user_id = $profile_data['user_id'];
    return $this->get_user_profile_by_id($user_id);
  }
}