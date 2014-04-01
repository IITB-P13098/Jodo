Hi <?php echo $disp_name; ?>,
Welcome to <?php echo $site_name; ?>,

To verify your email address, please follow this link:

<?php echo base_url('/auth/verify_email/'.$user_id.'/'.$email_key); ?>

Your email address: <?php echo $email; ?>

Have fun!
The <?php echo $site_name; ?> Team