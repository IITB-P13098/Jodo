<?php
/*
@charset "utf-8";

/******************************** Reset ********************************/
html, body, div, h1, h2, h3, h4, h5, h6, ul, ol, dl, li, dt, dd, p, blockquote,  
pre, form, fieldset, table, th, td { margin: 0; padding: 0; border: 0px; }

/******************************** Basic Elements ********************************/
html{display: block;}

body{margin:0;text-decoration:none;font-family:Segoe UI, Trebuchet, Arial, Sans-Serif;font-weight:normal;text-decoration:none;}
body.Zpage{margin-top:0;}

table, tr, td{margin:0;padding:0;}

object:focus{outline:none;}form{margin:0;}
.clear { clear:both; margin:0px; padding:0px;}

h1{font-family: 'Open Sans', Sans-Serif;font-size:50px;font-weight:normal;margin:0 0 0 7px;text-transform:lowercase;width:750px;padding-top:15px;padding-bottom:25px;}
h1.h1alternate{font-size:50px;margin-left:0;}
h2{font-family: 'Open Sans', Sans-Serif;color:#333;font-size:30px;font-weight:normal;line-height:36px;margin:0 0 9px 0;text-transform:lowercase;}
h3{color:#333;font-size:14px;margin:-2px 0 0 0;}
h4{color:#333;font-size:12px;text-transform:uppercase;}
h5{color:#555;font-size:20px;text-transform:uppercase;}
h6{color:#333;font-size:38px;font-weight:normal;text-transform:uppercase;}

ul{list-style-type:none;list-style-position:outside;margin:0;padding:0;}

img{border:none;}
a{color:#ec008c;text-decoration:none;}
a:hover{color:#7B006D;text-decoration:none;}

select, textarea, input {
  border: 1px solid #DDD;
  padding: 5px;
  text-align: left;
  vertical-align: middle;
  font-family: Segoe UI, Trebuchet, Arial, Sans-Serif;
}

.story-frame {
  width: 1000px;
  margin: 0 auto;
}

.story-frame .parent img, .story-frame .child  img {
  width: 150px;
  height: 150px;
}
.story-frame .parent {
  float: left;
  position: absolute;
}
.story-frame .child-list {
  float: right;
}

.story-frame .current {
  position: relative;
  float: left;
  left: 200px;
  width: 600px;
}
.story-frame .current img {
  max-width: 600px;
  max-height: 600px;
  margin-bottom: 20px;
}

*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/style.css"/>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
  <title>JODO - <?php echo $browse_data['story_data']['story']['title']; ?></title>
</head>
<body>

<div id="container">
  <div class="story-frame">
    <h1><?php echo $browse_data['story_data']['story']['title']; ?></h1>

    <div class="current">
    <img src="<?php echo base_url('images/story/'.$browse_data['story_data']['story']['image_id']); ?>">
    <p><?php echo $browse_data['story_data']['story']['description']; ?></p>
    </div>

    <div class="parent">
    <?php
    if (!empty($browse_data['story_data']['parent_page']))
    {
      ?>
      <a href="<?php echo base_url('story/index/'.$browse_data['story_data']['parent_page']['page_id']); ?>">
      <img src="<?php echo base_url('images/story/'.$browse_data['story_data']['parent_page']['image_id']); ?>">
      </a>
      <?php
    }
    ?>
    </div>

    <div class="child-list">
    <?php
    foreach ($browse_data['story_data']['child_list'] as $c)
    {
      ?>
      <div class="child">
      <a href="<?php echo base_url('story/index/'.$c['page_id']); ?>">
      <img src="<?php echo base_url('images/story/'.$c['image_id']); ?>">
      </a>
      </div>
      <?php
    }
    ?>
    </div>
  </div>
</div>

</body>
</html>