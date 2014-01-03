<?php //var_dump($story_data); ?>

<div class="col-xs-12">

  <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-7">
      <img class="img-responsive" src="<?php echo base_url('uploads/'.$story_data['story']['file_name']); ?>">
    </div>
    <div class="col-xs-2"></div>
    <div class="col-xs-1"></div>
  </div>

  <div class="row">
    <div class="col-xs-7 col-xs-offset-2">
      <blockquote>
        <p><?php echo $story_data['story']['caption']; ?></p>
      </blockquote>

      <div class="media">
        <a class="pull-left" href="#">
          <div class="media-object thumb" style="background-image: url('<?php echo 'http://rimebeta.com/do/file/thumbnail/'.(!empty($story_data['user']['profile_image_id']) ? $story_data['user']['profile_image_id'] : '0').'/s/profile'; ?>'); width:50px; height:50px;"></div>
        </a>
        <div class="media-body">
          <h4 class="media-heading"><a href="#"><?php echo $story_data['user']['disp_name']; ?></a> | <small><?php echo $story_data['user']['username']; ?></small></h4>
          <?php echo $story_data['user']['bio']; ?>
        </div>
      </div>
    </div>
  </div>
  
</div>

<!--
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/style.css"/>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
  <title>JODO - <?php echo $browse_data['story_data']['story']['title']; ?></title>
</head>
<body>

<div id="container">
  <div class="story-frame">
    <h1><?php echo $browse_data['story_data']['story']['title']; ?></h1>

    <div class="current">
    <img src="<?php echo base_url('images/story/'.$browse_data['story_data']['story']['image_id']); ?>">
    <p><?php echo $browse_data['story_data']['story']['description']; ?></p>
    </div>

    <div class="parent">
    <?php
    if (!empty($browse_data['story_data']['parent_page']))
    {
      ?>
      <a href="<?php echo base_url('story/index/'.$browse_data['story_data']['parent_page']['page_id']); ?>">
      <img src="<?php echo base_url('images/story/'.$browse_data['story_data']['parent_page']['image_id']); ?>">
      </a>
      <?php
    }
    ?>
    </div>

    <div class="child-list">
    <?php
    foreach ($browse_data['story_data']['child_list'] as $c)
    {
      ?>
      <div class="child">
      <a href="<?php echo base_url('story/index/'.$c['page_id']); ?>">
      <img src="<?php echo base_url('images/story/'.$c['image_id']); ?>">
      </a>
      </div>
      <?php
    }
    ?>
    </div>
  </div>
</div>

</body>
</html>
!-->