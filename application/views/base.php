<!DOCTYPE html>

<html lang="en">
  <head>
    <title><?php if (!empty($page_title)) echo $page_title.' - '; ?>JODO</title>
    <meta charset="utf-8">

    <!--non-responsive <meta name="viewport" content="width=device-width, initial-scale=1.0"> !-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.0.1/css/bootstrap.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script>
    
    <!--[if lt IE 9]>
      <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/fontello-entypo/css/fontello.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/fontello-entypo/css/animation.css'); ?>">
    <!--[if lt IE 7]>
      <link rel="stylesheet" href="<?php echo base_url('assets/fonts/fontello-entypo/css/fontello-ie7.css'); ?>">
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css')?>"/>
  </head>

  <body>
    <?php include('header.php'); // include is faster than include_once ?>

    <?php 
    if (!empty($main_content))
    {
      ?>
      <section class="main">
        <div class="container">
          <div class="row">
            <?php echo $main_content; ?>
          </div>
        </div>
      </section>
      <?php
    }
    ?>

    <?php include('footer.php'); ?>

    <!-- resposive thumb !-->
    <script type="text/javascript">
      function repos(imgs) {
          imgs.each(function (i, o) {
              //alert("parent width="+$(o).parent().width());

              var size = $(o).parent().width();

              var a = 1;
              if ($(o).attr('data-aspectratio')) a = $(o).attr('data-aspectratio');

              $(o).css('width', size);
              $(o).css('height', size / a);
          })
      }

      $(window).resize(function () {
          repos($('.rect-responsive'))
      })

      $(function() {
        repos($('.rect-responsive'))
      });

      //repos($('.rect-responsive'))
    </script>

  </body>
</html>