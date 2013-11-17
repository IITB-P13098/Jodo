<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_story
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    $this->ci->load->database();
    $this->ci->load->model('model_page');
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
  
  public function get_data($page_id)
  {
    $story = $this->ci->model_page->get_story_data_by_id($page_id);
    if (empty($story))
    {
      $this->error = array('message' => 'story not found');
      return NULL;
    }

    $data['story'] = $story;
    $data['parent_page'] = NULL;
    if (!empty($story['parent_page_id']))
    {
      $data['parent_page'] = $this->ci->model_page->get_data_by_id($story['parent_page_id']);
    }

    $data['child_list'] = $this->ci->model_page->get_child_list($page_id);

    return $data;
  }
}