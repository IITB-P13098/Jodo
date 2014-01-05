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
      $data['delete_url'] = 'do_story/delete/'.$story_id.'/1?redirect='.$redirect;

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

  function edit_title($story_id)
  {
  }

  function edit_caption($story_id)
  {
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */