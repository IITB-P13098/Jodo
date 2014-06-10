<!DOCTYPE html>

<html lang="en">
  <head>
    <title><?php if (!empty($page_title)) echo $page_title.' - '; ?>String Your Story</title>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://netdna.bootstrapcdn.com/bootstrap/3.0.1/js/bootstrap.min.js"></script>
    
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>

    <!--[if lt IE 7]>
      <link rel="stylesheet" href="<?php echo base_url('assets/fonts/fontello-entypo/css/fontello-ie7.css'); ?>">
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css')?>"/>
  </head>

  <body>
    <?php $this->view('header'); // include is faster than include_once ?>

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

    <?php $this->view('footer'); ?>

  </body>
</html>