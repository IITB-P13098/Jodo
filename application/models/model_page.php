<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_page extends CI_Model
{
  private $page_table     = 'page';
  private $story_table    = 'story';
  
  public function get_story_data_by_id($page_id)
  {
    $this->db->select($this->page_table.'.*');
    $this->db->select($this->story_table.'.story_id');
    $this->db->select($this->story_table.'.title');
    $this->db->select($this->story_table.'.created');
    
    $this->db->from($this->page_table);
    $this->db->join($this->story_table, $this->page_table.'.story_id = '.$this->story_table.'.story_id');
    
    $this->db->where('page_id', $page_id);
    
    $query = $this->db->get();
    return $query->row_array();
  }
  
  public function get_data_by_id($page_id)
  {
    $this->db->where('page_id', $page_id);
    
    $query = $this->db->get($this->page_table);
    return $query->row_array();
  }
  
  public function get_child_list($page_id)
  {
    $this->db->where('parent_page_id', $page_id);
    
    $query = $this->db->get($this->page_table);
    return $query->result_array();
  }
}