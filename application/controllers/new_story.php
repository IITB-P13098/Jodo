<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_story extends CI_Controller
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

          redirect('story/'.$page_id);
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

    $data['main_content'] = $this->load->view('new_story', $data, TRUE);
    $this->load->view('base', $data);
  }
}
