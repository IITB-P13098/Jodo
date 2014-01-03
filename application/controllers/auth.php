<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
  public function index()
  {
    redirect('/auth/signin/');
  }
  
  public function rime_signin()
  {
    $this->load->library('tank_auth');
    if ($this->tank_auth->is_logged_in()) 
    {
      redirect();
    }
    
    $this->load->library('lib_rime');
    if (!$this->lib_rime->get_request_token())
    {
      show_error($this->lib_rime->get_error_message());
    }
  }
  
  public function callback()
  {
    $this->load->library('lib_rime');
    if (!is_null($token = $this->lib_rime->get_access_token()))
    {
      if (!is_null($profile_data = $this->lib_rime->get_user_profile($token['access_token'])))
      {
        if ($this->tank_auth->login($profile_data))
        {
          redirect('');
        }
        else
        {
          show_error($this->tank_auth->get_error_message());
        }
      }
      else
      {
        show_error($this->lib_rime->get_error_message());
      }
    }
    else
    {
      show_error($this->lib_rime->get_error_message());
    }
  }
  
  function signout()
  {
    $this->load->library('tank_auth');
    $this->tank_auth->logout();

    redirect();
  }
}