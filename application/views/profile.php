<div class="col-xs-12">
  <div class="row">
    <div class="col-xs-12">
      
      <div class="user-head media">
        <a class="pull-left" href="<?php echo base_url('user/id/'.$req_user_data['user_id']); ?>">
          <img class="media-object img-responsive" src="<?php echo base_url((!empty($req_user_data['profile_image_id'])) ? $req_user_data['profile_image_id'] : 'assets/img/user-profile.png'); ?>" style="width:32px; height:32px;">
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