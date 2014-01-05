<?php
$title = array(
  'name'        => 'title',
  'id'          => 'title',
  'value'       => set_value('title', $title),
  'maxlength'   => $this->config->item('title_max_length', 'story'),
  'class'       => 'form-control',
);
?>

<?php echo form_open($this->uri->uri_string()); ?>

<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="myModalLabel">Edit title</h4>
    </div>
    <div class="modal-body">
      
      <div class="form-group <?php if (!empty($error[$title['name']])) echo 'has-error'; ?>">
        <?php echo form_label('Title', $title['id'], array('class' => 'control-label')); ?>
        <?php echo form_input($title); ?>
        <?php if (!empty($error[$title['name']])) { ?><span class="help-block"><?php echo $error[$title['name']]; ?></span><?php } ?>
      </div>

    </div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-sm-5">
          <button type="submit" class="btn btn-primary btn-block">Rename</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php echo form_close(); ?>