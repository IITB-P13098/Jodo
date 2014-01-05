<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="myModalLabel"><?php echo $heading; ?></h4>
    </div>
    <div class="modal-body"><?php echo $message; ?></div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-sm-5">
          <a href="<?php echo base_url($delete_url); ?>" class="btn btn-danger btn-block" role="button"><?php echo $heading; ?></a>
        </div>
      </div>
    </div>
  </div>
</div>