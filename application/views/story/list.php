<?php //var_dump($story); ?>

<div class="col-xs-12">

  <div class="story-list row">
    <?php
    foreach ($story as $s)
    {
      ?>
      <div class="col-xs-3">
        <div class="thumbnail">
          <a class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$s['file_name']); ?>');" title="<?php echo $s['title']; ?>" href="<?php echo base_url('story/index/'.$s['story_id']); ?>"></a>
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