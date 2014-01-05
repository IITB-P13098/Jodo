<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_story_title extends CI_Model
{
  private $story_title_table  = 'story_title';
  private $story_table        = 'story';
  private $images_table       = 'images';

  function create($story_id, $title)
  {
    $this->db->set('story_id', $story_id);
    $this->db->set('title', $title);

    $this->db->insert($this->story_title_table);
    return $this->db->insert_id();
  }

  function get_count()
  {
    $this->db->where('parent_story_id', NULL);
    return $this->db->count_all_results($this->story_table);
  }

  function get_recent($per_page, $page_id = 0)
  {
    $this->db->limit($per_page, $page_id * $per_page);

    $this->db->order_by('story_id', 'DESC');

    $this->db->select($this->story_table.'.*');
    $this->db->select($this->story_title_table.'.title');
    $this->db->select($this->images_table.'.file_name');
    
    $this->db->from($this->story_table);
    $this->db->join($this->story_title_table, $this->story_table.'.start_story_id = '.$this->story_title_table.'.story_id');
    $this->db->join($this->images_table, $this->story_table.'.story_id = '.$this->images_table.'.story_id');
    
    $this->db->where('parent_story_id', NULL);
    
    $query = $this->db->get();
    return $query->result_array();
  }
}