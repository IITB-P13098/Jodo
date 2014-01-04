<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_page extends CI_Model
{
  private $story_table        = 'story';
  private $story_title_table  = 'story_title';
  private $images_table       = 'images';
  
  function create($user_id, $caption, $parent_story_id = NULL, $start_story_id = NULL)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('caption', $caption);
    $this->db->set('parent_story_id', $parent_story_id);
    $this->db->set('start_story_id', $start_story_id);

    $this->db->insert($this->story_table);
    return $this->db->insert_id();
  }

  public function get_story_data_by_id($story_id)
  {
    $this->db->select($this->story_table.'.*');
    $this->db->select($this->story_title_table.'.title');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->story_table);
    $this->db->join($this->story_title_table, $this->story_title_table.'.story_id = IF('.$this->story_table.'.start_story_id IS NULL, '.$this->story_table.'.story_id, '.$this->story_table.'.start_story_id)');
    $this->db->join($this->images_table, $this->story_table.'.story_id = '.$this->images_table.'.story_id');
    
    $this->db->where($this->story_table.'.story_id', $story_id);
    
    $query = $this->db->get();
    return $query->row_array();
  }
  
  public function get_data_by_id($story_id)
  {
    $this->db->select($this->story_table.'.*');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->story_table);
    $this->db->join($this->images_table, $this->story_table.'.story_id = '.$this->images_table.'.story_id');
    
    $this->db->where($this->story_table.'.story_id', $story_id);
    
    $query = $this->db->get();
    return $query->row_array();
  }
  
  public function get_child_list($story_id, $per_page = 3, $index = 0)
  {
    $this->db->limit($per_page, $index * $per_page);

    $this->db->select($this->story_table.'.*');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->story_table);
    $this->db->join($this->images_table, $this->story_table.'.story_id = '.$this->images_table.'.story_id');
    
    $this->db->where('parent_story_id', $story_id);
    
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_child_count($story_id)
  {
    $this->db->where('parent_story_id', $story_id);
    return $this->db->count_all_results($this->story_table);
  }

  function get_user_pages_count($user_id)
  {
    $this->db->where('user_id', $user_id);
    return $this->db->count_all_results($this->story_table);
  }

  function get_user_pages($user_id, $per_page = 5, $index = 0)
  {
    $this->db->limit($per_page, $index * $per_page);

    $this->db->select($this->story_table.'.*');
    $this->db->select($this->story_title_table.'.title');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->story_table);
    $this->db->join($this->story_title_table, $this->story_title_table.'.story_id = IF('.$this->story_table.'.start_story_id IS NULL, '.$this->story_table.'.story_id, '.$this->story_table.'.start_story_id)');
    $this->db->join($this->images_table, $this->story_table.'.story_id = '.$this->images_table.'.story_id');
    
    $this->db->where($this->story_table.'.user_id', $user_id);
    
    $query = $this->db->get();
    return $query->result_array();
  }
}