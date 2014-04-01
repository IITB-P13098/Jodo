<div class="col-xs-12">
  <div class="row">
    <div class="col-xs-12">
      
      <div class="user-head media">
        <a class="pull-left" href="<?php echo base_url('user/id/'.$req_user_data['user_id']); ?>">
          <div class="media-object thumb" style="background-image: url('<?php echo 'http://rimebeta.com/do/file/thumbnail/'.(!empty($req_user_data['profile_image_id']) ? $req_user_data['profile_image_id'] : '0').'/s/profile'; ?>'); width:50px; height:50px;"></div>
        </a>
        <div class="media-body">
          <h4 class="media-heading"><a href="<?php echo base_url('user/id/'.$req_user_data['user_id']); ?>"><?php echo $req_user_data['disp_name']; ?></a></h4>
          <small><?php echo $req_user_data['bio']; ?></small>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <?php echo $main_content; ?>
  </div>
</div>