<?php
$caption = array(
  'name'          => 'caption',
  'id'            => 'caption',
  'value'         => set_value('caption', $caption),
  'maxlength'     => $this->config->item('caption_max_length', 'story'),
  'style'         => 'resize: none',
  'rows'          => 8,
  'class'         => 'form-control',
);
?>

<?php echo form_open($this->uri->uri_string()); ?>

<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="myModalLabel">Edit caption</h4>
    </div>
    <div class="modal-body">
      
      <div class="form-group <?php if (!empty($error[$caption['name']])) echo 'has-error'; ?>">
        <?php echo form_label('Caption', $caption['id'], array('class' => 'control-label')); ?>
        <?php echo form_textarea($caption); ?>
        <?php if (!empty($error[$caption['name']])) { ?><span class="help-block"><?php echo $error[$caption['name']]; ?></span><?php } ?>
      </div>

    </div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-sm-5">
          <button type="submit" class="btn btn-primary btn-block">Post</button>
        </div>
      </div>
    </div>
  </div>
</div>