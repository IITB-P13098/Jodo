<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Story extends CI_Controller 
{
  public function index($page_id = 0)
  {
    $this->load->library('lib_story');

    if (is_null($story_data = $this->lib_story->get_data($page_id)))
    {
      show_error($this->lib_story->get_error_message());
    }

    $data['browse_data']['story_data'] = $story_data;
    $this->load->view('story', $data);
  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */