Hi <?php if (!empty($username)) echo $username; ?>,

You have changed your email address for <?php echo $site_name; ?>.
Follow this link to confirm your new email address:

<?php echo base_url('/auth/update_email/'.$verify_id.'/'.$email_key); ?>


Your new email: <?php echo $email; ?>


You received this email, because it was requested by a <?php echo $site_name; ?> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.


Thank you,
The <?php echo $site_name; ?> Team