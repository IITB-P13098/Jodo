<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lib_user_profile
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    $this->ci->load->config('user_profile', TRUE);
    
    $this->ci->load->database();
    $this->ci->load->model('model_profile');
  }
  
  /**
   * Get error message.
   * Can be invoked after any failed operation such as login or register.
   *
   * @return  string
   */
  function get_error_message()
  {
    return $this->error;
  }
  
  function get_user_profile_by_id($user_id, $user_id_2 = '')
  {
    $this->ci->load->model('social/model_people_graph');
    $this->ci->load->model('social/model_activity');

    $profile_data = $this->ci->model_profile->get_user_profile_by_id($user_id);
    if (empty($profile_data))
    {
      $this->error = array('message' => 'invalid user id');
      return NULL;
    }
    
    $this->ci->load->library('social/lib_people_graph');
    $profile_data['relation'] = $this->ci->lib_people_graph->get_relation($user_id, $user_id_2);
    $profile_data['stat'] = array(
      'posts'     => $this->ci->model_activity->get_post_count($user_id),
      'following' => $this->ci->model_people_graph->get_following_count($user_id),
      'followers' => $this->ci->model_people_graph->get_followers_count($user_id),
    );

    $this->ci->load->library('social/lib_text_process');
    $profile_data['bio_text_data'] = $this->ci->lib_text_process->process_output_comment($profile_data['bio']);
    
    return $profile_data;
  }
  
  function get_user_profile_by_username($username, $user_id_2 = '')
  {
    $profile_data = $this->ci->model_profile->get_user_profile_by_username($username);
    if (empty($profile_data))
    {
      $this->error = array('message' => 'invalid username');
      return NULL;
    }
    
    $user_id = $profile_data['user_id'];
    return $this->get_user_profile_by_id($user_id, $user_id_2);
  }
  
  function _format_date($day, $month, $year)
  {
    $options = $this->ci->config->item('day_options', 'user_profile');
    if (empty($options[$day]))    return NULL;
    $day = sprintf('%02d', $day);
    
    $options = $this->ci->config->item('month_options', 'user_profile');
    if (empty($options[$month]))  return NULL;
    $month = sprintf('%02d', $month);
    
    $options = $this->ci->config->item('year_options', 'user_profile');
    if (empty($options[$year]))   return NULL;
    $year = sprintf('%04d', $year);
    
    $timestamp = $year.'-'.$month.'-'.$day.' 00:00:00';
    
    //if ($timestamp != '0000-00-00 00:00:00' AND checkdate($month, $day, $year) == FALSE)
    //{
    //  return NULL;
    //}
    
    //$time = strtotime('2013-09-05');
    //var_dump( date("M Y", $time) ); die();
    
    return $timestamp;
  }
  
  function get_user_profile_work($user_id)
  {
    $work_data = $this->ci->model_profile->get_profile_work($user_id);
    return $work_data;
  }
  
  function get_user_profile_edu($user_id)
  {
    $edu_data = $this->ci->model_profile->get_profile_edu($user_id);
    return $edu_data;
  }
  
  function update_profile($user_id, $disp_name, $bio, $location, $gender, $relationship_stat, $birthday_day, $birthday_month,  $birthday_year)
  {
    $options = $this->ci->config->item('gender_options', 'user_profile');
    if (!empty($gender) AND empty($options[$gender]))
    {
      $this->error = array('gender' => 'incorret gender');
      return NULL;
    }
    
    $options = $this->ci->config->item('relationship_stat_options', 'user_profile');
    if (!empty($relationship_stat) AND empty($options[$relationship_stat]))
    {
      $this->error = array('relationship_stat' => 'incorret status');
      return NULL;
    }
    
    if (is_null($birthday = $this->_format_date($birthday_day, $birthday_month, $birthday_year)))
    {
      $this->error = array('birthday' => 'incorret date');
      return NULL;
    }
    
    if ($birthday != '0000-00-00 00:00:00' AND checkdate($birthday_month, $birthday_day, $birthday_year) == FALSE)
    {
      $this->error = array('birthday' => 'incorret date');
      return NULL;
    }
    
    $this->ci->load->helper(array('text'));
    
    $bio = htmlspecialchars($bio);
    $bio = ascii_to_entities($bio);
    
    $this->ci->load->library('social/lib_text_process');
    $text_data = $this->ci->lib_text_process->process_input_comment($bio);
    
    $profile_data = array(
      'disp_name'         => $disp_name,
      'bio'               => $text_data['mod_text'],
      'location'          => $location,
      'gender'            => $gender,
      'relationship_stat' => $relationship_stat,
      'birthday'          => $birthday,
    );
    
    $this->ci->model_profile->update_profile($user_id, $profile_data);
    return TRUE;
  }
  
  function add_profile_work_edu($user_id, $type, $employer, $position, $location,
    $start_date_day, $start_date_month, $start_date_year,
    $end_date_day,   $end_date_month,   $end_date_year
  )
  {
    if (is_null($start_date = $this->_format_date($start_date_day, $start_date_month, $start_date_year)))
    {
      $this->error = array('start_date' => 'incorret date');
      return NULL;
    }
    
    if (is_null($end_date = $this->_format_date($end_date_day, $end_date_month, $end_date_year)))
    {
      $this->error = array('end_date' => 'incorret date');
      return NULL;
    }
    
    $work_data = array(
      'type'        => $type,
      'employer'    => $employer,  
      'position'    => $position,
      'location'    => $location,
      'start_date'  => $start_date,
      'end_date'    => $end_date,
    );
    
    //var_dump($work_data);
    
    if ($end_date == '0000-00-00 00:00:00') $end_date = date('Y-m-d H:i:s');
    
    if ($end_date < $start_date)
    {
      $this->error = array('end_date' => 'incorret date');
      return NULL;
    }
    
    $this->ci->model_profile->add_profile_work($user_id, $work_data);
    return TRUE;
  }
  
  public function remove_profile_work_edu($user_id, $work_id)
  {
    $this->ci->model_profile->remove_profile_work_edu($user_id, $work_id);
  }
}