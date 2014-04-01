<?php
$new_password = array(
  'name'        => 'new_password',
  'id'          => 'new_password',
  'maxlength'   => $this->config->item('password_max_length', 'tank_auth'),
  'class'       => 'form-control',
);
$confirm_new_password = array(
  'name'        => 'confirm_new_password',
  'id'          => 'confirm_new_password',
  'maxlength'   => $this->config->item('password_max_length', 'tank_auth'),
  'class'       => 'form-control',
);

$form_error = form_error($new_password['name']);
if (!empty($form_error)) $error[$new_password['name']] = $form_error;
if (!empty($error[$new_password['name']])) $new_password['id'] = 'inputError';

$form_error = form_error($confirm_new_password['name']);
if (!empty($form_error)) $error[$confirm_new_password['name']] = $form_error;
if (!empty($error[$confirm_new_password['name']])) $confirm_new_password['id'] = 'inputError';
?>

<?php echo form_open(uri_string()); ?>

<div class="form-group <?php if (!empty($error[$new_password['name']])) echo 'has-error'; ?>">
  <?php echo form_label('New Password', $new_password['id'], array('class' => 'control-label')); ?>
  <?php echo form_password($new_password); ?>
  <?php if (!empty($error[$new_password['name']])) { ?><span class="help-block"><?php echo $error[$new_password['name']]; ?></span><?php } ?>
</div>

<div class="form-group <?php if (!empty($error[$confirm_new_password['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Confirm New Password', $confirm_new_password['id'], array('class' => 'control-label')); ?>
  <?php echo form_password($confirm_new_password); ?>
  <?php if (!empty($error[$confirm_new_password['name']])) { ?><span class="help-block"><?php echo $error[$confirm_new_password['name']]; ?></span><?php } ?>
</div>

<div class="row">
  <div class="col-sm-5">
    <button type="submit" class="btn btn-primary btn-block">Change Password</button>
  </div>
</div>

<?php echo form_close(); ?>