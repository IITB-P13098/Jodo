<?php //var_dump($story_data); ?>

<div class="col-xs-12">

  <div class="row">
    <div class="col-xs-2">
      <?php
      if (!empty($story_data['parent_story']))
      {
        ?>
        <a href="<?php echo base_url('story/index/'.$story_data['parent_story']['story_id']); ?>">
          <div class="thumbnail">
            <div class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$story_data['parent_story']['file_name']); ?>');"></div>
          </div>
        </a>
        <?php
      }
      ?>
    </div>
    <div class="col-xs-7">
      <img class="img-responsive" src="<?php echo base_url('uploads/'.$story_data['story']['file_name']); ?>">
    </div>
    <div class="col-xs-3">
      <?php
      foreach ($story_data['child_list'] as $c)
      {
        ?>
        <div class="row">
          <div class="col-xs-8">
            <a href="<?php echo base_url('story/index/'.$c['story_id']); ?>">
              <div class="thumbnail">
                <div class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$c['file_name']); ?>');"></div>
              </div>
            </a>
          </div>
          <div class="col-xs-4">
            <?php
            foreach ($c['child_list'] as $cc)
            {
              ?>
              <a href="<?php echo base_url('story/index/'.$cc['story_id']); ?>">
                <div class="thumbnail">
                  <div class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$cc['file_name']); ?>');"></div>
                </div>
              </a>
              <?php
            }
            ?>
          </div>
        </div>
        <?php
      }
      ?>

      <?php
      if ($is_logged_in)
      {
        ?>
        <div class="row">
          <div class="col-xs-8">
            <a href="<?php echo base_url('story/add/'.$story_data['story']['story_id']); ?>">
              <img class="img-responsive" src="http://placehold.it/350&text=Add+Page">
            </a>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-7 col-xs-offset-2">
      <blockquote>
        <p><?php echo $story_data['story']['caption']; ?></p>
      </blockquote>

      <div class="media">
        <a class="pull-left" href="<?php echo base_url('home/profile/'.$story_data['user']['username']); ?>">
          <div class="media-object thumb" style="background-image: url('<?php echo 'http://rimebeta.com/do/file/thumbnail/'.(!empty($story_data['user']['profile_image_id']) ? $story_data['user']['profile_image_id'] : '0').'/s/profile'; ?>'); width:50px; height:50px;"></div>
        </a>
        <div class="media-body">
          <h4 class="media-heading"><a href="<?php echo base_url('home/profile/'.$story_data['user']['username']); ?>"><?php echo $story_data['user']['disp_name']; ?></a> | <small><?php echo $story_data['user']['username']; ?></small></h4>
          <?php echo $story_data['user']['bio']; ?>
        </div>
      </div>
    </div>
  </div>
  
</div>