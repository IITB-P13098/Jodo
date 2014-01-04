<div class="col-xs-12">
  <div class="row">
    <div class="col-xs-3">
      <?php
      if ($is_logged_in)
      {
        ?>
        <div class="thumbnail">
          <a href="<?php echo base_url('story/compose'); ?>">
            <img class="img-responsive" src="http://placehold.it/350x350">
          </a>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="row">
    <?php echo $main_content; ?>
  </div>
</div>