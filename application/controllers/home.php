<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
  public function index()
  {
    $data = array();

    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');
    
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_user_profile_by_id($user_id);
    }

    $this->load->library('lib_story');
    $story_data = $this->lib_story->get_recent();

    $data['main_content'] = $this->load->view('story/list', $story_data, TRUE);
    $data['main_content'] = $this->load->view('home', $data, TRUE);
    $this->load->view('base', $data);
  }

  public function profile($username = '')
  {
    $data = array();

    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');
    
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_user_profile_by_id($user_id);
    }

    $this->load->library('lib_user_profile');
    $data['req_user_data'] = $this->lib_user_profile->get_user_profile_by_username($username);

    if (empty($data['req_user_data']))
    {
      show_error('invalid username');
    }

    $this->load->library('lib_story');
    $story_data = $this->lib_story->get_users_recent($data['req_user_data']['user_id']);

    $data['main_content'] = $this->load->view('story/list', $story_data, TRUE);
    $data['main_content'] = $this->load->view('profile', $data, TRUE);
    $this->load->view('base', $data);
  }
}
