<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_page extends CI_Model
{
  private $page_table     = 'page';
  private $story_table    = 'story';
  private $images_table   = 'images';
  
  function create($user_id, $caption, $image_id, $story_id, $parent_page_id = NULL)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('caption', $caption);
    $this->db->set('image_id', $image_id);
    $this->db->set('story_id', $story_id);
    $this->db->set('parent_page_id', $parent_page_id);

    $this->db->insert($this->page_table);
    return $this->db->insert_id();
  }

  public function get_story_data_by_id($page_id)
  {
    $this->db->select($this->page_table.'.*');
    $this->db->select($this->story_table.'.story_id');
    $this->db->select($this->story_table.'.title');
    //$this->db->select($this->story_table.'.created');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->page_table);
    $this->db->join($this->story_table, $this->page_table.'.story_id = '.$this->story_table.'.story_id');
    $this->db->join($this->images_table, $this->page_table.'.image_id = '.$this->images_table.'.image_id');
    
    $this->db->where('page_id', $page_id);
    
    $query = $this->db->get();
    return $query->row_array();
  }
  
  public function get_data_by_id($page_id)
  {
    $this->db->select($this->page_table.'.*');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->page_table);
    $this->db->join($this->images_table, $this->page_table.'.image_id = '.$this->images_table.'.image_id');
    
    $this->db->where('page_id', $page_id);
    
    $query = $this->db->get();
    return $query->row_array();
  }
  
  public function get_child_list($page_id, $index = 0, $limit = 3)
  {
    $this->db->limit($limit, $index);

    $this->db->select($this->page_table.'.*');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->page_table);
    $this->db->join($this->images_table, $this->page_table.'.image_id = '.$this->images_table.'.image_id');
    
    $this->db->where('parent_page_id', $page_id);
    
    $query = $this->db->get();
    return $query->result_array();
  }

  public function get_child_count($page_id)
  {
    $this->db->where('parent_page_id', $page_id);
    return $this->db->count_all_results($this->page_table);
  }
}