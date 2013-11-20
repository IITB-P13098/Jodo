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