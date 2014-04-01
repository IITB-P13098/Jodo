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

  /**
   * Get username
   *
   * @return  string
   */
  function get_username()
  {
    $user_id = $this->get_user_id();
    if (!empty($user_id))
    {
      $user = $this->ci->users->get_user_by_id($user_id);
      return $user['username'];
    }
    return NULL;
  }

  function _verify_captcha()
  {
    // no captcha found
    if (empty($_POST["recaptcha_challenge_field"])) return 'TRUE';
    
    $this->ci->load->helper('recaptchalib');
    $this->ci->load->config('tank_auth', TRUE);
    
    $privatekey = $this->ci->config->item('recaptcha_private_key', 'tank_auth');
    $resp = recaptcha_check_answer ($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);
    
    if (!$resp->is_valid)
    {
      //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
      //     "(reCAPTCHA said: " . $resp->error . ")");
      return FALSE; // the CAPTCHA was entered incorrectly
    }
    else
    {
      //die ("reCAPTCHA successful verification");
      return TRUE; // successful verification
    }
  }
  
  /**
   * Create new user on the site and return some data about it:
   * user_id, username, password, email, new_email_key (if any).
   *
   * @param  string
   * @param  string
   * @param  string
   * @param  bool
   * @return  array
   */
  function create_user($disp_name, $email, $password)
  {
    $this->error = array();
    
    if (!$this->ci->users->is_email_available($email))
    {
      $this->error = array('email' => 'email in use');
      return NULL;
    }
    else if (!$this->_verify_captcha())
    {
      $this->error = array('captcha' => 'incorrect captcha');
      return NULL;
    }
    
    // Hash password using phpass
    $hasher = new PasswordHash(
        $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
        $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
    $hashed_password = $hasher->HashPassword($password);
    
    $email_key = md5(rand().microtime());

    $user_data = array(
      'password'      => $hashed_password,
      'email'         => $email,
      'email_key'     => $email_key,
      'last_ip'       => $this->ci->input->ip_address(),
    );
    
    $this->ci->db->trans_start();

    $user_id = $this->ci->users->create_user($user_data);

    $this->ci->load->model('model_profile');
    $this->ci->model_profile->create($user_id, $disp_name);
    
    $this->ci->db->trans_complete();

    $user_data['user_id'] = $user_id;
    unset($user_data['password']);
    unset($user_data['last_ip']);
    
    $this->login($email, $password, TRUE);

    return $user_data;
  }

  function _refresh_email_key($email)
  {
    $email_key = md5(rand().microtime());
    $this->ci->users->update_email_key($email, $email_key);
    
    return $email_key;
  }
  
  function verify_email($user_id, $email_key)
  {
    $user = $this->ci->users->verify_email_key($user_id, $email_key);

    if (!empty($user))
    {
      return $user;
    }
    else
    {
      $this->error = array('message' => 'Verification failed: Id not found');
    }
    return NULL;
  }

  function email_verified($user_id)
  {
    $this->ci->users->email_verified($user_id);
  }
  
  /**
   * Login user on the site. Return TRUE if login is successful
   * (user exists and activated, password is correct), otherwise FALSE.
   *
   * @param  string  (username or email or both depending on settings in config file)
   * @param  string
   * @param  bool
   * @return  bool
   */
  function login($email, $password, $remember)
  {
    if ($this->_verify_captcha())
    {
      $user = $this->ci->users->get_user_by_email($email);
      if (!empty($user))
      {
        // login ok
        
        // Does password match hash in database?
        $hasher = new PasswordHash(
          $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
          $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
        
        if ($hasher->CheckPassword($password, $user['password']))
        {
          // password ok
          
          // success
          $this->ci->session->set_userdata(array(
            'user_id'   => $user['user_id'],
            'status'    => '1',
          ));

          if ($remember)
          {
            $this->_create_autologin($user['user_id']);
          }
          
          $this->clear_login_attempts($email);

          $this->ci->users->update_login_info($user['user_id']);
          return TRUE;
        }
        else
        {
          // fail - wrong password
          $this->increase_login_attempt($email);
          $this->error = array('password' => 'incorrect password');
        }
      }
      else
      {
        // fail - wrong login
        $this->increase_login_attempt($email);
        $this->error = array('login' => 'incorrect login');
      }
    }
    else
    {
      // fail - wrong captcha
      $this->increase_login_attempt($email);
      $this->error = array('captcha' => 'incorrect captcha');
    }
  
    return NULL;
  }

  function update_email($user_id, $email)
  {
    $this->ci->users->update_email($user_id, $email);
  }
  
  function forgot_password($email)
  {
    $user = $this->ci->users->get_user_by_email($email);
    if (!empty($user))
    {
      $user['email_key'] = $this->_refresh_email_key($email);
      return $user;
    }
    else
    {
      $this->error = array('email' => 'incorrect email');
    }
    return NULL;
  }
  
  /**
   * Replace user password (forgotten) with a new one (set by user)
   * and return some data about it: user_id, username, new_password, email.
   *
   * @param  string
   * @param  string
   * @return  bool
   */
  function reset_password($user_id, $new_password)
  {
    $user = $this->ci->users->get_user_by_id($user_id);
    if (!empty($user))
    {
      // Hash password using phpass
      $hasher = new PasswordHash(
        $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
        $this->ci->config->item('phpass_hash_portable', 'tank_auth'));
      $hashed_password = $hasher->HashPassword($new_password);
      
      $this->ci->db->trans_start();

      $this->ci->users->update_password($user['user_id'], $hashed_password);
      
      $this->ci->users->update_email_key($user['email'], NULL);

      // Clear all user's autologins
      $this->ci->load->model('tank_auth/user_autologin');
      $this->ci->user_autologin->clear($user['user_id']);

      $this->ci->db->trans_complete();

      return $user;
    }
    else
    {
      $this->error = array('message' => 'user id not found');
    }

    return NULL;
  }
  
  function change_password($user_id, $password, $new_password)
  {
    $user = $this->ci->users->get_user_by_id($user_id);
    if (!empty($user))
    {
      // Hash password using phpass
      $hasher = new PasswordHash(
        $this->ci->config->item('phpass_hash_strength', 'tank_auth'),
        $this->ci->config->item('phpass_hash_portable', 'tank_auth')
      );
      
      if ($hasher->CheckPassword($password, $user['password']))
      {
        // password ok
        $hashed_password = $hasher->HashPassword($new_password);
        $this->ci->users->update_password($user_id, $hashed_password);
        
        return array(
          'user_id'       => $user_id,
          'username'      => $user['username'],
          'email'         => $user['email'],
          //'new_password'  => $new_password,
        );
      }
      else
      {
        // fail - wrong password
        $this->error = array('password' => 'incorrect password');
      }
    }
    return NULL;
  }
  
  function update_username($user_id, $new_username)
  {
    $user = $this->ci->users->get_user_by_username($new_username);
    
    if (empty($user))
    {
      $this->ci->users->update_username($user_id, $new_username);
      return TRUE;
    }
    else
    {
      if ($user['user_id'] != $user_id)
      {
        $this->error = array('username' => 'already taken');
      }
      else
      {
        $this->error = array('username' => 'that\'s you');
      }
    }
    return NULL;
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
  
  /**
   * Check if login attempts exceeded max login attempts (specified in config)
   *
   * @param  string
   * @return  bool
   */
  function is_max_login_attempts_exceeded($email)
  {
    if ($this->ci->config->item('login_count_attempts', 'tank_auth'))
    {
      $this->ci->load->model('tank_auth/login_attempts');
      return $this->ci->login_attempts->get_attempts_num($this->ci->input->ip_address(), $email) >= $this->ci->config->item('login_max_attempts', 'tank_auth');
    }
    return FALSE;
  }
  
  /**
   * Increase number of attempts for given IP-address and login
   * (if attempts to login is being counted)
   *
   * @param  string
   * @return  void
   */
  private function increase_login_attempt($email)
  {
    if ($this->ci->config->item('login_count_attempts', 'tank_auth'))
    {
      if (!$this->is_max_login_attempts_exceeded($email))
      {
        $this->ci->load->model('tank_auth/login_attempts');
        $this->ci->login_attempts->increase_attempt($this->ci->input->ip_address(), $email);
      }
    }
  }
  
  /**
   * Clear all attempt records for given IP-address and login
   * (if attempts to login is being counted)
   *
   * @param  string
   * @return  void
   */
  private function clear_login_attempts($email)
  {
    if ($this->ci->config->item('login_count_attempts', 'tank_auth'))
    {
      $this->ci->load->model('tank_auth/login_attempts');
      $this->ci->login_attempts->clear_attempts(
        $this->ci->input->ip_address(),
        $email,
        $this->ci->config->item('login_attempt_expire', 'tank_auth')
      );
    }
  }
}