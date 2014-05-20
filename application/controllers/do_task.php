<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Do_task extends CI_Controller 
{
  function __construct()
  {
    parent::__construct();
    
    $this->load->library('tank_auth');
    $this->load->library('lib_user_profile');

    // die ('here');
    
    if (!$this->tank_auth->is_logged_in()) 
    {
      redirect('');
    }
  }

  function _clean_up($pass_code = '')
  {
    if ($pass_code != '3cb295d7fdd496b15a3602406d83551c') exit('oops');
    
    $dir = './uploads';
    $files = scandir($dir);
    
    // var_dump($files);

    $this->load->model('model_task');
    for ($i = 3; $i < count($files) - 1; $i++)
    {
      $file_data = $this->model_task->do_exists($files[$i]);
      if (empty($file_data))
      {
        $image_path = './uploads/'.$files[$i];
        if (file_exists($image_path))
        {
          echo "unlink".$image_path."<br/>";
          // unlink($image_path);
        }
      }
    }
  }
}