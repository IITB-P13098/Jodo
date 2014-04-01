<?php
$email = array(
  'name'        => 'email',
  'id'          => 'email',
  'value'       => set_value('email'),
  'maxlength'   => $this->config->item('email_max_length', 'tank_auth'),
  'type'        => 'email', // html5 tag - not supported in Internet Explorer 9 and earlier versions.
  'class'       => 'form-control',
);

$form_error = form_error($email['name']);
if (!empty($form_error)) $error[$email['name']] = $form_error;
if (!empty($error[$email['name']])) $email['id'] = 'inputError';
?>

<?php echo form_open(uri_string()); ?>

<div class="form-group <?php if (!empty($error[$email['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Email Address', $email['id'], array('class' => 'control-label')); ?>
  <?php echo form_input($email); ?>
  <?php if (!empty($error[$email['name']])) { ?><span class="help-block"><?php echo $error[$email['name']]; ?></span><?php } ?>
</div>

<div class="row">
  <div class="col-sm-5">
    <button type="submit" class="btn btn-primary btn-block">Send</button>
  </div>
</div>

<?php echo form_close(); ?>