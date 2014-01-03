<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package    Tank_auth
 * @author    Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version    1.0.9
 * @based on  DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license    MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    $this->ci->load->config('tank_auth', TRUE);
    
    $this->ci->load->library('session');
    $this->ci->load->database();
    $this->ci->load->model('tank_auth/users');
    
    // Try to autologin
    $this->_autologin();
  }
  
  /**
   * Save data for user's autologin
   *
   * @param  int
   * @return  bool
   */
  private function _create_autologin($user_id)
  {
    $this->ci->load->helper('cookie');
    $key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);
    
    $this->ci->load->model('tank_auth/user_autologin');
    $this->ci->user_autologin->purge($user_id);
    
    if ($this->ci->user_autologin->set($user_id, md5($key)))
    {
      set_cookie(array(
        'name'    => $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
        'value'   => serialize(array('user_id' => $user_id, 'key' => $key)),
        'expire'  => $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
      ));
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * Clear user's autologin data
   *
   * @return  void
   */
  private function _delete_autologin()
  {
    $this->ci->load->helper('cookie');
    if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE))
    {
      $cookie_data = unserialize($cookie);
      
      $this->ci->load->model('tank_auth/user_autologin');
      $this->ci->user_autologin->delete($cookie_data['user_id'], md5($cookie_data['key']));
      
      delete_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'));
    }
  }
  
  /**
   * Login user automatically if he/she provides correct autologin verification
   *
   * @return  void
   */
  private function _autologin()
  {
    if (!$this->is_logged_in()) // not logged in (as any user)
    {
      $this->ci->load->helper('cookie');
      
      if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE))
      {
        $cookie_data = unserialize($cookie);
        
        if (isset($cookie_data['key']) AND isset($cookie_data['user_id']))
        {
          $this->ci->load->model('tank_auth/user_autologin');
          
          $user = $this->ci->user_autologin->get($cookie_data['user_id'], md5($cookie_data['key']));
          if (!empty($user))
          {
            // Login user
            $this->ci->session->set_userdata(array(
              'user_id'   => $user['user_id'],
              'status'    => '1',
            ));
            
            // Renew users cookie to prevent it from expiring
            set_cookie(array(
              'name'      => $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
              'value'     => $cookie,
              'expire'    => $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
            ));
            
            $this->ci->users->update_login_info(
              $user['user_id'],
              $this->ci->config->item('login_record_ip', 'tank_auth'),
              $this->ci->config->item('login_record_time', 'tank_auth')
            );
            
            return TRUE;
          }
        }
      }
    }
    return FALSE;
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
   * Check if user logged in.
   *
   * @return  bool
   */
  public function is_logged_in()
  {
    return $this->ci->session->userdata('status');
  }
  
  /**
   * Get user_id
   *
   * @return  string
   */
  function get_user_id()
  {
    return $this->ci->session->userdata('user_id');
  }
 
  function login($profile_data, $remember = TRUE)
  {
    $user_id = $profile_data['user_id'];

    $user = $this->ci->users->get_user_by_id($user_id);
    if (empty($user))
    {
      $this->ci->load->model('model_profile');

      $this->ci->db->trans_start();
      $this->ci->users->create_user($user_id, $profile_data['username']);
      $this->ci->model_profile->create_profile($user_id, $profile_data['disp_name']);
      $this->ci->db->trans_complete();
    }
    else
    {
      $this->ci->load->model('model_profile');

      $this->ci->db->trans_start();
      $this->ci->users->update_user($user_id, $profile_data['username']);
      $this->ci->model_profile->update_profile($user_id, $profile_data['disp_name'], $profile_data['bio'], $profile_data['profile_image_id'], $profile_data['cover_image_id']);
      $this->ci->db->trans_complete();
    }

    $this->ci->session->set_userdata(array(
      'user_id'   => $user_id,
      'status'    => '1',
    ));
    
    if ($remember)
    {
      $this->_create_autologin($user_id);
    }

    $this->ci->users->update_login_info($user_id);
    return TRUE;
  }
  
  /**
   * Logout user from the site
   *
   * @return  void
   */
  function logout()
  {
    $this->_delete_autologin();
    
    // See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
    $this->ci->session->set_userdata(array('user_id' => '', 'status' => ''));
    
    $this->ci->session->sess_destroy();
    
    //destroy cookies
  }
}