<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_story extends CI_Model
{
  private $story_table = 'story';
  
  function create($user_id, $title)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('title', $title);

    $this->db->insert($this->story_table);
    return $this->db->insert_id();
  }
}