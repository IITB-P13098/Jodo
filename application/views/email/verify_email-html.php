<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>Welcome to <?php echo $site_name; ?>!</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Verdana, sans-serif;">
Hi <?php echo $disp_name; ?>,<br/>
<h2 style="font: normal 20px/23px Verdana, sans-serif; margin: 0; padding: 0 0 18px; color: black;">Welcome to <?php echo $site_name; ?>!</h2>
To verify your email address, please follow this link:<br />
<br />
<big style="font: 16px/18px Verdana, sans-serif;"><b><a href="<?php echo base_url('/auth/verify_email/'.$user_id.'/'.$email_key); ?>" style="color: #3366cc;">Finish your registration</a></b></big><br />
<br />
Link doesn't work? Copy the following link to your browser address bar:<br />
<nobr><a href="<?php echo base_url('/auth/verify_email/'.$user_id.'/'.$email_key); ?>" style="color: #3366cc;"><?php echo base_url('/auth/verify_email/'.$user_id.'/'.$email_key); ?></a></nobr><br />
<br />
Your email address: <?php echo $email; ?><br />
<br />
<br />
Have fun!<br />
The <?php echo $site_name; ?> Team
</td>
</tr>
</table>
</div>
</body>
</html>