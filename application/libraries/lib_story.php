<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_story
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    $this->ci->load->database();
    $this->ci->load->model('model_story');
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

  function create($user_id, $title, $cover_image_data, $image_data, $cover_caption = '', $caption = '')
  {
    $this->ci->load->helper(array('text'));
    
    $title = htmlspecialchars($title);
    $title = ascii_to_entities($title);
    
    $cover_caption = htmlspecialchars($cover_caption);
    $cover_caption = ascii_to_entities($cover_caption);

    $caption = htmlspecialchars($caption);
    $caption = ascii_to_entities($caption);

    $this->ci->db->trans_start();
    //cover story
    $this->ci->load->model('model_story');
    $story_id = $this->ci->model_story->create($user_id, $cover_caption);

    $this->ci->load->model('model_image');
    $this->ci->model_image->create($story_id, $cover_image_data);

    $this->ci->load->model('model_story_title');
    $this->ci->model_story_title->create($story_id, $title);

    //first page
    $child_story_id = $this->ci->model_story->create($user_id, $caption, $story_id, $story_id);

    $this->ci->model_image->create($child_story_id, $image_data);
    $this->ci->db->trans_complete();

    return $story_id;
  }

  function add($user_id, $parent_story_id, $start_story_id, $image_data, $caption = '')
  {
    $this->ci->load->helper(array('text'));
    
    if ($parent_story_id == NULL)
    {
      $this->error = array('message' => 'cannot add to cover image');
      return NULL;
    }

    $caption = htmlspecialchars($caption);
    $caption = ascii_to_entities($caption);

    $this->ci->db->trans_start();
    $this->ci->load->model('model_story');
    $story_id = $this->ci->model_story->create($user_id, $caption, $parent_story_id, $start_story_id);

    $this->ci->load->model('model_image');
    $image_id = $this->ci->model_image->create($story_id, $image_data);
    $this->ci->db->trans_complete();

    return $story_id;
  }

  public function get_data($story_id)
  {
    $story = $this->ci->model_story->get_story_data_by_id($story_id);
    if (empty($story))
    {
      $this->error = array('message' => 'story not found');
      return NULL;
    }

    return $story;
  }
  
  public function get_all_data($story_id, $page_id = 0)
  {
    $story = $this->ci->model_story->get_story_data_by_id($story_id);
    if (empty($story))
    {
      $this->error = array('message' => 'story not found');
      return NULL;
    }

    $data['story'] = $story;
    $data['parent_story'] = NULL;
    if (!empty($story['parent_story_id']))
    {
      $data['parent_story'] = $this->ci->model_story->get_data_by_id($story['parent_story_id']);
    }

    $data['child_list'] = $this->ci->model_story->get_child_list($story_id, 3, $page_id);
    foreach ($data['child_list'] as $key => $value)
    {
      $data['child_list'][$key]['child_list'] = $this->ci->model_story->get_child_list($value['story_id'], 2);
    }

    $data['child_count'] = $this->ci->model_story->get_child_count($story_id);

    $this->ci->load->library('lib_user_profile');
    $data['user'] = $this->ci->lib_user_profile->get_by_id($story['user_id']);

    return $data;
  }

  function delete($story_id, $user_id)
  {
    $child_list = $this->ci->model_story->get_child_list($story_id);
    if (!empty($child_list))
    {
      $this->error = array('message' => 'can not delete when child exists');
      return NULL;
    }

    $story = $this->ci->model_story->get_story_data_by_id($story_id);
    if ($story['parent_story_id'] == $story['start_story_id'])
    {
      $this->ci->model_story->purge_by_id($story['start_story_id'], $user_id);
      return TRUE;
    }
    else
    {
      $this->ci->model_story->purge_by_id($story_id, $user_id);
      return TRUE;
    }
  }

  function edit_title($story_id, $user_id, $title)
  {
    $this->ci->load->helper(array('text'));

    $title = htmlspecialchars($title);
    $title = ascii_to_entities($title);

    $story = $this->ci->model_story->get_story_data_by_id($story_id);
    if (empty($story))
    {
      $this->error = array('message' => 'story not found');
      return NULL;
    }

    if ($story['user_id'] != $user_id)
    {
      $this->error = array('message' => 'not the owner');
      return NULL;
    }

    if ($story['parent_story_id'] != NULL)
    {
      $this->error = array('message' => 'not the cover page');
      return NULL;
    }

    //$this->ci->model_story->update_title($story['start_story_id'], $title);
    $this->ci->model_story->update_title($story['story_id'], $title);
    return TRUE;
  }

  function edit_caption($story_id, $user_id, $caption)
  {
    $this->ci->load->helper(array('text'));

    $caption = htmlspecialchars($caption);
    $caption = ascii_to_entities($caption);
    
    $this->ci->model_story->update_caption($story_id, $user_id, $caption);
    return TRUE;
  }

  function get_recent($stories_per_page, $page_id = 0)
  {
    $this->ci->load->model('model_story_title');
    $data['story'] = $this->ci->model_story_title->get_recent($stories_per_page, $page_id);
    $data['count'] = $this->ci->model_story_title->get_count();

    return $data;
  }

  function get_users_recent($user_id, $stories_per_page, $page_id = 0)
  {
    $this->ci->load->model('model_story');
    $data['story'] = $this->ci->model_story->get_user_stories($user_id, $stories_per_page, $page_id);
    $data['count'] = $this->ci->model_story->get_user_stories_count($user_id);

    return $data;
  }
}