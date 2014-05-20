<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Do_story extends CI_Controller 
{
  function __construct()
  {
    parent::__construct();
    
    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');

    if (!$this->tank_auth->is_logged_in()) 
    {
      redirect('');
    }
  }

  function delete($story_id, $confirm = '')
  {
    $redirect = !empty($_GET['redirect']) ? $_GET['redirect'] : '';

    if (empty($confirm))
    {
      if (!$this->input->is_ajax_request())
      {
        exit('No direct script access allowed');
      }
      
      $data['heading'] = 'Delete';
      $data['message'] = 'Are you sure you want to delete this story?';
      $data['delete_url'] = 'do_story/delete/'.$story_id.'/1?redirect='.rawurlencode($redirect);

      $this->load->view('modals/delete', $data);
      return;
    }

    $user_id = $this->tank_auth->get_user_id();

    $this->load->library('lib_story');
    if (is_null($this->lib_story->delete($story_id, $user_id)))
    {
      show_error($this->lib_story->get_error_message());
    }
    
    redirect($redirect);
  }

  function edit_title($story_id, $title = '')
  {
    $user_id = $this->tank_auth->get_user_id();

    $data['title'] = rawurldecode($title);

    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('security');

    $this->load->config('story', TRUE);
    
    $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean|max_length['.$this->config->item('title_max_length', 'story').']');

    if ($this->form_validation->run())
    {
      $this->load->library('lib_story');
      $this->lib_story->edit_title($story_id, $user_id, $this->form_validation->set_value('title'));
      
      redirect('story/id/'.$story_id);
    }

    $this->load->view('modals/title', $data);
  }

  function edit_caption($story_id)
  {
    $user_id = $this->tank_auth->get_user_id();

    $this->load->library('lib_story');
    if (is_null($story_data = $this->lib_story->get_data($story_id)))
    {
      show_error($this->lib_story->get_error_message());
    }

    $data['caption'] = html_entity_decode($story_data['caption']);

    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->load->library('security');

    $this->load->config('story', TRUE);
    $this->form_validation->set_rules('caption', 'Caption', 'trim|xss_clean|max_length['.$this->config->item('caption_max_length', 'story').']');

    if ($this->form_validation->run())
    {
      $this->load->library('lib_story');
      $this->lib_story->edit_caption($story_id, $user_id, $this->form_validation->set_value('caption'));
      
      redirect('story/id/'.$story_id);
    }

    $this->load->view('modals/caption', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */