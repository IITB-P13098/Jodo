<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_task extends CI_Model
{
  private $images_table = 'images';
  
  function do_exists($file_name)
  {
    $this->db->where('file_name', $file_name);

    $query = $this->db->get($this->images_table);
    return $query->row_array();
  }
}