<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Story extends CI_Controller 
{
  public function index($page_id = 0)
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

    if (is_null($story_data = $this->lib_story->get_data($page_id)))
    {
      show_error($this->lib_story->get_error_message());
    }

    $data['story_data'] = $story_data;
    $data['page_title'] = $story_data['story']['title'];

    $data['main_content'] = $this->load->view('story/main', $data, TRUE);
    $data['main_content'] = $this->load->view('story/base', $data, TRUE);
    $this->load->view('base', $data);
  }

  public function add($page_id = 0)
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
    else
    {
      redirect();
    }

    $this->load->library('lib_story');

    if (is_null($story_data = $this->lib_story->get_data($page_id)))
    {
      show_error($this->lib_story->get_error_message());
    }

    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('security');

    $this->load->config('story', TRUE);
    
    $this->load->library('upload');

    $this->form_validation->set_rules('caption', 'Caption', 'trim|xss_clean|max_length['.$this->config->item('caption_max_length', 'story').']');
    
    if ($this->form_validation->run())
    {
      if ($this->upload->do_upload())
      {
        $file_data = $this->upload->data();

        if ($file_data['is_image'])
        {
          $this->load->library('lib_story');
          $page_id = $this->lib_story->add_page($user_id, $story_data['story']['story_id'], $story_data['story']['page_id'], $file_data, $this->form_validation->set_value('caption'));

          redirect('story/index/'.$page_id);
        }
        else
        {
          $data['error']['userfile'] = 'uploaded file is not an image';
        }
      }
      else
      {
        $data['error']['userfile'] = $this->upload->display_errors();
      }
    }

    $data['story_data'] = $story_data;
    $data['page_title'] = $story_data['story']['title'];

    $data['main_content'] = $this->load->view('story/add_form', $data, TRUE);
    $data['main_content'] = $this->load->view('story/add', $data, TRUE);
    $data['main_content'] = $this->load->view('story/base', $data, TRUE);
    $this->load->view('base', $data);
  }

  public function compose()
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
    else
    {
      redirect();
    }

    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('security');

    $this->load->config('story', TRUE);
    
    $this->load->library('upload');

    $data['page_title'] = 'New Story';

    $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean|max_length['.$this->config->item('title_max_length', 'story').']');
    $this->form_validation->set_rules('caption', 'Caption', 'trim|xss_clean|max_length['.$this->config->item('caption_max_length', 'story').']');
    
    if ($this->form_validation->run())
    {
      if ($this->upload->do_upload())
      {
        $file_data = $this->upload->data();

        if ($file_data['is_image'])
        {
          $this->load->library('lib_story');
          $page_id = $this->lib_story->create($user_id, $this->form_validation->set_value('title'), $file_data, $this->form_validation->set_value('caption'));

          redirect('story/index/'.$page_id);
        }
        else
        {
          $data['error']['userfile'] = 'uploaded file is not an image';
        }
      }
      else
      {
        $data['error']['userfile'] = $this->upload->display_errors();
      }
    }

    $data['main_content'] = $this->load->view('story/compose', $data, TRUE);
    $data['main_content'] = $this->load->view('story/base', $data, TRUE);
    $this->load->view('base', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */