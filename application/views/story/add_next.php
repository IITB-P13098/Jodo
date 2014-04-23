<div class="col-xs-12">
  <div class="row">
    
    <div class="col-xs-2">
      <a href="<?php echo base_url('story/id/'.$story_data['story']['story_id']); ?>">
        <div class="thumbanil">
          <img class="img-responsive" src="<?php echo base_url('timthumb/timthumb.php?src=uploads/'.$story_data['story']['file_name']); ?>&w=150&h=150">
        </div>
      </a>
    </div>
    <div class="col-xs-8">
      <?php echo $main_content; ?>
    </div>
  </div>
</div>