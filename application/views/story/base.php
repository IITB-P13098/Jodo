<div class="col-xs-12">
  <div class="story-title">
    <h1><?php echo anchor('story/index/'.$story_data['story']['start_story_id'], $story_data['story']['title']); ?></h1>
  </div>
  
  <div class="row">
    <?php echo $main_content; ?>
  </div>  
</div>
