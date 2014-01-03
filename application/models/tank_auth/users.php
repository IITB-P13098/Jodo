<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package  Tank_auth
 * @author  Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
  private $table_name = 'users';  // user accounts

  /**
   * Get user record by Id
   *
   * @param  int
   * @param  bool
   * @return  object
   */
  function get_user_by_id($user_id)
  {
    $this->db->where('user_id', $user_id);

    $query = $this->db->get($this->table_name);
    return $query->row_array();
  }

  /**
   * Get user record by username
   *
   * @param  string
   * @return  object
   */
  function get_user_by_username($username)
  {
    $this->db->where('LOWER(username)=', strtolower($username));

    $query = $this->db->get($this->table_name);
    return $query->row_array();
  }

  /**
   * Create new user record
   *
   * @param  array
   * @param  bool
   * @return  array
   */
  function create_user($user_id, $username)
  {
    $this->db->set('user_id', $user_id);
    $this->db->set('username', $username);
    $this->db->set('created', NULL); // date('Y-m-d H:i:s');
    
    $this->db->insert($this->table_name);
  }

  function update_user($user_id, $username)
  {
    $this->db->set('username', $username);
    $this->db->where('user_id', $user_id);
    
    $this->db->update($this->table_name);
  }
  
  /**
   * Update user login info, such as IP-address or login time, and
   * clear previously generated (but not activated) passwords.
   *
   * @param  int
   * @param  bool
   * @param  bool
   * @return  void
   */
  function update_login_info($user_id)
  {
    $this->db->set('last_ip', $this->input->ip_address());
    $this->db->set('last_login', date('Y-m-d H:i:s'));

    $this->db->where('user_id', $user_id);
    $this->db->update($this->table_name);
  }
}
/* End of file users.php */
/* Location: ./application/models/auth/users.php */