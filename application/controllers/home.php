<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{
  public function index()
  {
    $this->recent();
  }

  public function recent($page_id = 0)
  {
    $data = array();

    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');
    
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_by_id($user_id);
    }

    $this->load->config('story', TRUE);
    $stories_per_page = $this->config->item('stories_per_page', 'story');

    $this->load->library('lib_story');
    $story_data = $this->lib_story->get_recent($stories_per_page, $page_id);

    $next_page = floor(($story_data['count']-1) / $stories_per_page) > $page_id ? $page_id+1 : 0;
    if (!empty($next_page)) $story_data['next_page'] = 'home/index/'.$next_page;
    
    $data['main_content'] = $this->load->view('story/list', $story_data, TRUE);
    $data['main_content'] = $this->load->view('home', $data, TRUE);
    $this->load->view('base', $data);
  }

  public function popular($page_id = 0)
  {
    $data = array();

    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');
    
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_by_id($user_id);
    }

    $this->load->config('story', TRUE);
    $stories_per_page = $this->config->item('stories_per_page', 'story');

    $this->load->library('lib_story');
    $story_data = $this->lib_story->get_popular($stories_per_page, $page_id);

    $next_page = floor(($story_data['count']-1) / $stories_per_page) > $page_id ? $page_id+1 : 0;
    if (!empty($next_page)) $story_data['next_page'] = 'home/index/'.$next_page;
    
    $data['main_content'] = $this->load->view('story/list', $story_data, TRUE);
    $data['main_content'] = $this->load->view('home', $data, TRUE);
    $this->load->view('base', $data);
  }

  public function how()
  {
    $data = array();

    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');
    
    $user_id = 0;
    if ($data['is_logged_in'] = $this->tank_auth->is_logged_in())
    {
      $user_id = $this->tank_auth->get_user_id();
      $data['user_data'] = $this->lib_user_profile->get_by_id($user_id);
    }

    $data['main_content'] = $this->load->view('docs/about', $data, TRUE);
    $this->load->view('base', $data);
  }
}
