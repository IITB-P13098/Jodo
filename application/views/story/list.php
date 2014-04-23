<?php //var_dump($story); ?>

<div class="col-xs-12">

  <div class="story-list row">
    <?php
    foreach ($story as $s)
    {
      ?>
      <div class="col-xs-3">
        <div class="thumbnail">
          <a href="<?php echo base_url('story/id/'.$s['story_id']); ?>" title="<?php echo $s['title']; ?>">
            <img class="img-responsive" src="<?php echo base_url('timthumb/timthumb.php?src=uploads/'.$s['file_name']); ?>&w=250&h=250">
          </a>
          <div class="caption"><?php echo $s['title']; ?></div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>

  <?php
  if (!empty($next_page))
  {
    ?>
    <div class="row">
      <div class="col-xs-4 col-xs-offset-4">
        <a class="btn btn-primary btn-block" href="<?php echo base_url($next_page); ?>">More</a>
      </div>
    </div>
    <?php
  }
  ?>
  
</div>