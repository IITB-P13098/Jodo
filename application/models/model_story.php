<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_story extends CI_Model
{
  private $story_table = 'story';
  private $page_table = 'page';
  private $images_table   = 'images';

  function create($user_id, $title)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('title', $title);

    $this->db->insert($this->story_table);
    return $this->db->insert_id();
  }

  function get_count()
  {
    $this->db->where('parent_page_id', NULL);
    return $this->db->count_all_results($this->page_table);
  }

  function get_recent($per_page, $index)
  {
    $this->db->limit($per_page, $index * $per_page);

    $this->db->select($this->page_table.'.*');
    $this->db->select($this->story_table.'.title');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->page_table);
    $this->db->join($this->story_table, $this->page_table.'.story_id = '.$this->story_table.'.story_id');
    $this->db->join($this->images_table, $this->page_table.'.image_id = '.$this->images_table.'.image_id');
    
    $this->db->where('parent_page_id', NULL);
    
    $query = $this->db->get();
    return $query->result_array();
  }
}