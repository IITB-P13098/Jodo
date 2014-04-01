<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_profile extends CI_Model
{
  private $profile_table  = 'user_profiles';
  private $users_table    = 'users';
  
  /**
   * Create an empty profile for a new user
   *
   * @param  int
   * @return  bool
   */
  function create($user_id, $disp_name)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('disp_name', $disp_name);
    return $this->db->insert($this->profile_table);
  }
  
  function update($user_id, $disp_name, $bio, $profile_image_id, $cover_image_id)
  {
    $this->db->set('disp_name', $disp_name);
    $this->db->set('bio', $bio);
    $this->db->set('profile_image_id', $profile_image_id);
    $this->db->set('cover_image_id', $cover_image_id);

    $this->db->where('user_id', $user_id);
    $this->db->update($this->profile_table);
  }
  
  function get_by_id($user_id)
  {
    $this->db->select($this->users_table.'.user_id');
    $this->db->select($this->profile_table.'.*');
    $this->db->from($this->users_table);
    $this->db->join($this->profile_table, $this->profile_table.'.user_id = '.$this->users_table.'.user_id');
    $this->db->where($this->users_table.'.user_id', $user_id);
    $query = $this->db->get();
    
    return $query->row_array();
  }
}
/* End of file model_profile.php */
/* Location: ./application/models/model_profile.php */