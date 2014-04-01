<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function index()
  {
    redirect('auth/signin');
  }
  
  function _show_message($content, $heading = 'Success')
  {
    // user data for update email page
    $this->load->library('lib_user_profile');
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_by_id($user_id);
    }

    $local_data = array('heading' => $heading, 'content' => $content);
    $data['main_content'] = $this->load->view('auth/base', $local_data, TRUE);

    $data['hide_header'] = TRUE;
    $this->load->view('base', $data);
  }
  
  function _create_recaptcha()
  {
    $this->load->helper('recaptchalib');
    $this->load->config('tank_auth', TRUE);
    
    // Get reCAPTCHA HTML
    $html = recaptcha_get_html($this->config->item('recaptcha_public_key', 'tank_auth'));
    
    return $html;
  }
  
  public function signin()
  {
    $this->load->library('tank_auth');
    if ($this->tank_auth->is_logged_in()) 
    {
      redirect();
    }
    
    $this->load->helper(array('form'));
    $this->load->library('form_validation');
    $this->load->library('security');
    
    $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|max_length['.$this->config->item('email_max_length', 'tank_auth').']');
    
    $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');

    $this->form_validation->set_rules('remember', 'Remember me', 'integer');
    
    $data['error'] = array();
    $data['show_captcha'] = FALSE;
    
    if ($this->form_validation->run())
    {
      if ($this->tank_auth->login(
        $this->form_validation->set_value('email'),
        $this->form_validation->set_value('password'),
        $this->form_validation->set_value('remember')
        ))
      {
        redirect();
      }
      else
      {
        $data['error'] = $this->tank_auth->get_error_message();
      }
      
      if ($this->tank_auth->is_max_login_attempts_exceeded($this->form_validation->set_value('email')))
      {
        $data['show_captcha'] = TRUE;
        $data['recaptcha_html'] = $this->_create_recaptcha();
      }
    }
    
    $this->_show_message($this->load->view('auth/signin', $data, TRUE), 'Sign In');
  }
  
  /**
   * Logout user
   *
   * @return void
   */
  function signout()
  {
    $this->load->library('tank_auth');
    $this->tank_auth->logout();
    
    $this->_show_message('You have been successfully signed out. '.anchor('auth/signin', 'Sign in again!'), 'Signed Out');
  }
  
  function register()
  {
    $this->load->library('tank_auth');
    if ($this->tank_auth->is_logged_in()) 
    {
      redirect();
    }

    $this->load->helper(array('form'));
    $this->load->library('form_validation');
    $this->load->library('security');
    
    $this->load->config('user_profile', TRUE);
    $this->form_validation->set_rules('disp_name', 'Full Name', 'trim|required|xss_clean|strip_tags|max_length['.$this->config->item('disp_name_max_length', 'user_profile').']'); // |alpha_dash

    $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|max_length['.$this->config->item('email_max_length', 'tank_auth').']');
    
    $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
    
    $data['error'] = array();
    
    if ($this->form_validation->run())
    {
      if (!is_null($result = $this->tank_auth->create_user(
        $this->form_validation->set_value('disp_name'),
        $this->form_validation->set_value('email'),
        $this->form_validation->set_value('password')
        )))
      {
        $result['disp_name'] = $this->form_validation->set_value('disp_name');
        
        $this->load->library('lib_send_email');
        $this->lib_send_email->general('Verify your email', $result['email'], 'verify_email', $result);
        
        redirect();
      }
      else
      {
        $data['error'] = $this->tank_auth->get_error_message();
      }
    }
    
    $data['captcha_registration'] = $this->config->item('captcha_registration', 'tank_auth');
    if ($data['captcha_registration'])
    {
      $data['recaptcha_html'] = $this->_create_recaptcha();
    }
    
    $this->_show_message($this->load->view('auth/register', $data, TRUE), 'Register');
  }

  function verify_email($user_id, $email_key)
  {
    $this->load->library('tank_auth');
    if (!$this->tank_auth->is_logged_in()) 
    {
      show_error('You must login to verify your email.');
    }

    if (!is_null($user_data = $this->tank_auth->verify_email($user_id, $email_key)))
    {
      $this->tank_auth->email_verified($user_id);
      $this->_show_message('Email verifcation successful. '.anchor('settings', 'Settings'), 'Verifired');
    }
    else
    {
      show_error($this->tank_auth->get_error_message());
    }
  }
  
  /**
   * Generate reset code (to change password) and send it to user
   *
   * @return void
   */
  function forgot_password()
  {
    if ($this->tank_auth->is_logged_in())
    {
      redirect();
    }
    
    $this->load->helper(array('form'));
    $this->load->library('form_validation');
    $this->load->library('security');
    
    $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|max_length['.$this->config->item('email_max_length', 'tank_auth').']');
    
    $data['error'] = array();
    
    if ($this->form_validation->run())
    {
      // validation ok
      if (!is_null($result = $this->tank_auth->forgot_password(
          $this->form_validation->set_value('email'))))
      {
        // Send email with new password
        $this->load->library('lib_send_email');
        $this->lib_send_email->general('Change your password', $result['email'], 'forgot_password', $result);
        
        $this->_show_message('Check mail to reset password. '.anchor(uri_string(), 'Reset again'), 'Check Mail');
        return;
      }
      else
      {
        $data['error'] = $this->tank_auth->get_error_message();
      }
    }
    
    $this->_show_message($this->load->view('auth/forgot_password', $data, TRUE), 'Forgot Password');
  }
  
  /**
   * Replace user password (forgotten) with a new one (set by user).
   * User is verified by user_id and authentication code in the URL.
   * Can be called by clicking on link in mail.
   *
   * @return void
   */
  function reset_password($user_id, $email_key)
  {
    if (is_null($this->tank_auth->verify_email($user_id, $email_key)))
    {
      show_error($this->tank_auth->get_error_message());
    }

    $this->load->helper(array('form'));
    $this->load->library('form_validation');
    $this->load->library('security');
    
    $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
    
    $this->form_validation->set_rules('confirm_new_password', 'Confirm new Password', 'trim|required|xss_clean|matches[new_password]');
    
    $data['error'] = array();
    
    if ($this->form_validation->run())
    {
      // validation ok
      if (!is_null($result = $this->tank_auth->reset_password(
        $user_id,
        $this->form_validation->set_value('new_password'))))
      {
        // success
        $this->load->library('lib_send_email');
        $this->lib_send_email->general('You have reset your password successfully', $result['email'], 'reset_password', $result);
        
        $this->_show_message('Hurray got your new password. '.anchor('auth/signin', 'Sign in'), 'Reset Success');
        return;
      }
      else
      {
        show_error($this->tank_auth->get_error_message());
      }
    }
    $this->_show_message($this->load->view('auth/reset_password', $data, TRUE), 'Reset Password');
  }
}