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
   * Get user record by email
   *
   * @param  string
   * @return  object
   */
  function get_user_by_email($email)
  {
    $this->db->where('LOWER(email)=', strtolower($email));

    $query = $this->db->get($this->table_name);
    return $query->row_array();
  }

  /**
   * Check if email available for registering
   *
   * @param  string
   * @return  bool
   */
  function is_email_available($email)
  {
    $this->db->select('1', FALSE);
    $this->db->where('LOWER(email)=', strtolower($email));

    $query = $this->db->get($this->table_name);
    return $query->num_rows() == 0;
  }

  /**
   * Create new user record
   *
   * @param  array
   * @param  bool
   * @return  array
   */
  function create_user($data)
  {
    $data['created'] = NULL; // date('Y-m-d H:i:s');
    
    $this->db->insert($this->table_name, $data);    
    return $this->db->insert_id();
  }

  /**
   * Change user password if password key is valid and user is authenticated.
   *
   * @param  int
   * @param  string
   * @param  string
   * @param  int
   * @return  bool
   */
  function update_password($user_id, $password)
  {
    $this->db->set('password', $password);
    $this->db->where('user_id', $user_id);

    $this->db->update($this->table_name);
  }

  /**
   * Activate new email (replace old email with new one) if activation key is valid.
   *
   * @param  int
   * @param  string
   * @return  bool
   */
  function update_email($user_id, $email)
  {
    $this->db->set('email', $email);
    $this->db->where('user_id', $user_id);

    $this->db->update($this->table_name);
  }

  function update_email_key($email, $email_key)
  {
    $this->db->set('email_key', $email_key);
    $this->db->where('email', $email);

    $this->db->update($this->table_name);
  }

  function verify_email_key($user_id, $email_key)
  {
    $this->db->where('user_id', $user_id);
    $this->db->where('email_key', $email_key);

    $query = $this->db->get($this->table_name);
    return $query->row_array();
  }
  
  function email_verified($user_id)
  {
    $this->db->set('email_verified', TRUE);
    $this->db->set('email_key', NULL);
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