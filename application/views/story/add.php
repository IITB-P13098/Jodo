<div class="col-xs-12">
  <div class="row">
    
    <div class="col-xs-2">
      <a href="<?php echo base_url('story/index/'.$story_data['story']['page_id']); ?>">
        <div class="thumbanil">
          <div class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$story_data['story']['file_name']); ?>');"></div>
        </div>
      </a>
    </div>
    <div class="col-xs-8">
      <?php echo $main_content; ?>
    </div>
  </div>
</div>