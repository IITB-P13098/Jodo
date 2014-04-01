<?php
$email = array(
  'name'        => 'email',
  'id'          => 'email',
  'value'       => set_value('email'),
  'maxlength'   => $this->config->item('email_max_length', 'tank_auth'),
  'type'        => 'email', // html5 tag - not supported in Internet Explorer 9 and earlier versions.
  'class'       => 'form-control',
);
$password = array(
  'name'        => 'password',
  'id'          => 'password',
  'class'       => 'form-control',
);
$remember = array(
  'name'        => 'remember',
  'id'          => 'remember',
  'value'       => '1',
  'checked'     => 1, //set_value('remember'),
);

$form_error = form_error($email['name']);
if (!empty($form_error)) $error[$email['name']] = $form_error;
if (!empty($error[$email['name']])) $email['id'] = 'inputError';

$form_error = form_error($password['name']);
if (!empty($form_error)) $error[$password['name']] = $form_error;
if (!empty($error[$password['name']])) $password['id'] = 'inputError';
?>

<?php echo form_open(uri_string()); ?>

<div class="form-group <?php if (!empty($error[$email['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Email', $email['id'], array('class' => 'control-label')); ?>
  <?php echo form_input($email); ?>
  <?php if (!empty($error[$email['name']])) { ?><span class="help-block"><?php echo $error[$email['name']]; ?></span><?php } ?>
</div>
<div class="form-group <?php if (!empty($error[$password['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Password', $password['id'], array('class' => 'control-label')); ?>
  <?php echo form_password($password); ?>
  <?php if (!empty($error[$password['name']])) { ?><span class="help-block"><?php echo $error[$password['name']]; ?></span><?php } ?>
</div>
<div class="checkbox">
  <label class="control-label">
  <?php echo form_checkbox($remember); ?> Remember me
  </label>
</div>

<div class="form-group">
  <?php
  if ($show_captcha AND isset($recaptcha_html)) 
  {
    echo $recaptcha_html;
  }
  ?>
  <?php if (!empty($error['captcha'])) { ?><span class="help-block"><?php echo $error['captcha']; ?></span><?php } ?>
</div>

<div class="row">
  <div class="col-sm-5">
    <button type="submit" class="btn btn-primary btn-block">Sign in</button>
  </div>
</div>

<?php echo form_close(); ?>

<div style="margin-top: 20px">
  <?php echo anchor('/auth/forgot_password/', 'Forgot password'); ?>
</div>