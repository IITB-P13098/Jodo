<div class="col-xs-12">
  <div class="row">
    
    <div class="col-xs-6 col-md-2">
      <a href="<?php echo base_url('story/id/'.$story_data['story']['story_id']); ?>">
        <div class="thumbnail">
          <div class="bg-cover" style="background-image: url(<?php echo base_url('uploads/'.$story_data['story']['file_name']); ?>);">
            <img class="img-responsive" src="<?php echo base_url('assets/img/blank.png'); ?>" width="300">
          </div>
        </div>
      </a>
    </div>
    <div class="col-xs-12 col-md-8">
      <?php echo $main_content; ?>
    </div>
  </div>
</div>