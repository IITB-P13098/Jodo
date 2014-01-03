<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('services/service_base.php');
require_once('services/dropbox.php');
require_once('services/facebook.php');
require_once('services/flickr.php');
require_once('services/foursquare.php');
require_once('services/google.php');
require_once('services/instagram.php');
require_once('services/microsoft.php');
require_once('services/vimeo.php');
require_once('services/twitter.php');

class Lib_service
{
  private $error = array();
  
  function __construct()
  {
    $this->ci =& get_instance();
    
    $this->ci->load->database();
    //$this->ci->load->model('tank_auth/users');
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
  
  function get_request_token($user_id, $service_name)
  {
    // initiate based on $service_name
    $service_obj = new $service_name();
    if (!$service_obj->get_request_token($user_id))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    return TRUE;
  }
  
  function get_access_token($user_id, $service_name)
  {
    // initiate based on $service_name
    $service_obj = new $service_name();
    if (!$service_obj->get_access_token($user_id))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    return TRUE;
  }
  
  function revoke_access_token($user_id, $service_id)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    if ($service['user_id'] != $user_id)
    {
      $this->error = array('message' => 'invalid service owner');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (!$service_obj->revoke_access_token($user_id))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    return TRUE;
  }
  
  function get_services($user_id)
  {
    $this->ci->load->model('services/model_services');
    
    $services = $this->ci->model_services->get_services_by_user_id($user_id);
    return $services;
  }
  
  function get_user_info($service_id)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($user_info['data'] = $service_obj->get_user_info()))
    {
      $this->error = $service_obj->get_error_message();
      //return NULL;
    }
    
    $user_info['service'] = $service;
    
    return $user_info;
  }
  
  function get_album_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($album_list = $service_obj->get_album_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($album_list);
    
    $data['data'] = $album_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_album($service_id, $s_album_id, $visit_user_id = 0, $list = FALSE)
  {
    $page_id = 1;
    
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($photo_list = $service_obj->get_album($s_album_id, $visit_user_id, $list, $page_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $data['data'] = $photo_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_photo($service_id, $s_photo_id, $visit_user_id = 0, $list = FALSE)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($photo_data = $service_obj->get_photo($s_photo_id, $visit_user_id, $list)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $data['data'] = $photo_data;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_video_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($videos_list = $service_obj->get_video_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($videos_list);
    
    $data['data'] = $videos_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_video($service_id, $s_video_id, $visit_user_id = 0, $list = FALSE)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($video_data = $service_obj->get_video($s_video_id, $visit_user_id, $list)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $data['data'] = $video_data;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_files_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($files_list = $service_obj->get_files_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($files_list);
    
    $data['data'] = $files_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_file($service_id, $s_file_id, $visit_user_id = 0, $list = FALSE)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($file_data = $service_obj->get_file($s_file_id, $visit_user_id, $list)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $data['data'] = $file_data;
    $data['service'] = $service;
    
    return $data;
  }

  function get_auth_file($service_id, $url, $mime_type, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $service_obj->get_auth_file($url, $mime_type);
  }
  
  function get_blogs_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($blogs_list = $service_obj->get_blogs_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($blogs_list);
    
    $data['data'] = $blogs_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_badges_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($badges_list = $service_obj->get_badges_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($badges_list);
    
    $data['data'] = $badges_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function get_checkins_list($service_id, $visit_user_id = 0)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    if (is_null($checkins_list = $service_obj->get_checkins_list($visit_user_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    //var_dump($checkins_list);
    
    $data['data'] = $checkins_list;
    $data['service'] = $service;
    
    return $data;
  }
  
  function delete($service_id, $s_file_id, $method)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $method = 'delete_'.$method;
    if (is_null($file_data = $service_obj->$method($s_file_id)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $this->ci->load->model('services/model_services_cache');
    $this->ci->model_services_cache->purge_cache_data_by_service_id($service_id);
    
    return TRUE;
  }
  
  function change_privacy($service_id, $s_file_id, $method, $is_public)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $method = 'change_privacy_'.$method;
    if (is_null($file_data = $service_obj->$method($s_file_id, $is_public)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $this->ci->load->model('services/model_services_cache');
    $this->ci->model_services_cache->purge_cache_data_by_service_id($service_id);
    
    return TRUE;
  }
  
  public function rename($service_id, $s_file_id, $method, $name)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $method = 'rename_'.$method;
    if (is_null($file_data = $service_obj->$method($s_file_id, $name)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $this->ci->load->model('services/model_services_cache');
    $this->ci->model_services_cache->purge_cache_data_by_service_id($service_id);
    
    return TRUE;
  }
  
  function create_dir($user_id, $method, $service_id, $s_folder_id, $folder_name)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    if ($service['user_id'] != $user_id)
    {
      $this->error = array('message' => 'unauthorized user');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $method = 'create_'.$method;
    if (is_null($file_data = $service_obj->$method($s_folder_id, $folder_name)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $this->ci->load->model('services/model_services_cache');
    $this->ci->model_services_cache->purge_cache_data_by_service_id($service_id);
    
    return $file_data;
  }
  
  function upload_file($user_id, $method, $service_id, $s_folder_id, $upload_file_data)
  {
    $this->ci->load->model('services/model_services');
    $service = $this->ci->model_services->get_service_by_id($service_id);
    if (empty($service))
    {
      $this->error = array('message' => 'invalid service id');
      return NULL;
    }
    
    if ($service['user_id'] != $user_id)
    {
      $this->error = array('message' => 'unauthorized user');
      return NULL;
    }
    
    $service_obj = new $service['service_name']($service);
    $method = 'upload_'.$method;
    if (is_null($service_obj->$method($s_folder_id, $upload_file_data)))
    {
      $this->error = $service_obj->get_error_message();
      return NULL;
    }
    
    $this->ci->load->model('services/model_services_cache');
    $this->ci->model_services_cache->purge_cache_data_by_service_id($service_id);
    
    return TRUE;
  }
}