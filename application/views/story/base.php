<div class="col-xs-12">
  <div class="">
    <?php
    if (!empty($user_data))
    {
      if ($story_data['story']['user_id'] == $user_data['user_id'] AND $story_data['story']['parent_story_id'] == NULL)
      {
        $key_id = 'modal-edit-title-'.$story_data['story']['story_id'];
        $url = base_url('do_story/edit_title/'.$story_data['story']['story_id'].'/'.rawurlencode($story_data['story']['title']));
        ?>
        <a class="pull-right" data-toggle="modal" data-target="#<?php echo $key_id; ?>" href="<?php echo $url; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
        <div class="modal fade" id="<?php echo $key_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
        <?php
      }
    }
    ?>

    <h1 class="story-title"><?php echo anchor('story/id/'.$story_data['story']['start_story_id'], $story_data['story']['title']); ?></h1>
  </div>
  
  <div class="row">
    <?php echo $main_content; ?>
  </div>  
</div>
