<header>
  <div class="container">
    <nav class="navbar navbar-default" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo base_url(); ?>">Jodo</a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        
        <ul class="nav navbar-nav">
          <li><a href="#">Popular</a></li>
          <li><a href="#">Recent</a></li>
          <?php
          if ($is_logged_in)
          {
            ?>
            <li><a href="<?php echo base_url('home/profile/'.$user_data['username']); ?>">Me</a></li>
            <?php
          }
          ?>
        </ul>
        
        <form class="navbar-form navbar-right" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-default sr-only">Submit</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <?php
          if ($is_logged_in)
          {
            ?>
            <li><a href="<?php echo base_url('auth/signout'); ?>">Sign out</a></li>
            <?php
          }
          else
          {
            ?>
            <li><a href="<?php echo base_url('auth/rime_signin'); ?>">Sign in with Rime</a></li>
            <?php
          }
          ?>
        </ul>
      </div>
    </nav>
  </div>
</header>