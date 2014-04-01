<?php
$disp_name = array(
  'name'          => 'disp_name',
  'id'            => 'disp_name',
  'value'         => set_value('disp_name'),
  'maxlength'     => $this->config->item('disp_name_max_length', 'user_profile'),
  'placeholder'   => 'Your first and second name',
  'class'         => 'form-control',
);
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
  'value'       => set_value('password'),
  'maxlength'   => $this->config->item('password_max_length', 'tank_auth'),
  'placeholder' => $this->config->item('password_min_length', 'tank_auth').' characters or more! Be tricky.',
  'class'       => 'form-control',
);

$form_error = form_error($disp_name['name']);
if (!empty($form_error)) $error[$disp_name['name']] = $form_error;
if (!empty($error[$disp_name['name']])) $disp_name['id'] = 'inputError';

$form_error = form_error($email['name']);
if (!empty($form_error)) $error[$email['name']] = $form_error;
if (!empty($error[$email['name']])) $email['id'] = 'inputError';

$form_error = form_error($password['name']);
if (!empty($form_error)) $error[$password['name']] = $form_error;
if (!empty($error[$password['name']])) $password['id'] = 'inputError';

?>

<?php echo form_open(uri_string()); ?>

<div class="form-group <?php if (!empty($error[$disp_name['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Full Name', $disp_name['id'], array('class' => 'control-label')); ?>
  <?php echo form_input($disp_name); ?>
  <?php if (!empty($error[$disp_name['name']])) { ?><span class="help-block"><?php echo $error[$disp_name['name']]; ?></span><?php } ?>
</div>

<div class="form-group <?php if (!empty($error[$email['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Email Address', $email['id'], array('class' => 'control-label')); ?>
  <?php echo form_input($email); ?>
  <?php if (!empty($error[$email['name']])) { ?><span class="help-block"><?php echo $error[$email['name']]; ?></span><?php } ?>
</div>

<div class="form-group <?php if (!empty($error[$password['name']])) echo 'has-error'; ?>">
  <?php echo form_label('Password', $password['id'], array('class' => 'control-label')); ?>
  <?php echo form_password($password); ?>
  <?php if (!empty($error[$password['name']])) { ?><span class="help-block"><?php echo $error[$password['name']]; ?></span><?php } ?>
</div>

<div class="form-group">
  <?php
  if ($captcha_registration AND isset($recaptcha_html)) 
  {
    echo $recaptcha_html;
  }
  ?>
  <?php if (!empty($error['captcha'])) { ?><span class="help-block"><?php echo $error['captcha']; ?></span><?php } ?>
</div>

<div class="row">
  <div class="col-sm-5">
    <button type="submit" class="btn btn-primary btn-block">Register</button>
  </div>
</div>

<?php echo form_close(); ?>