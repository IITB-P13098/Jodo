<?php //var_dump($story); ?>

<div class="col-xs-12">

  <div class="story-list row">
    <?php
    foreach ($story as $s)
    {
      ?>
      <div class="col-xs-3">
        <div class="thumbnail">
          <a class="thumb rect-responsive" style="background-image: url('<?php echo base_url('uploads/'.$s['file_name']); ?>');"href="<?php echo base_url('story/index/'.$s['page_id']); ?>"></a>
          <div class="caption"><?php echo $s['title']; ?></div>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
  
</div>