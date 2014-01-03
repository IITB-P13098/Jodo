<?php $rime_oauth_url = 'http://rimebeta.com/api/oauth/authenticate/jodo'; ?>

<header>
  <nav class="navbar navbar-default" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Jodo</a>
      </div>

      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="#">Link A</a></li>
          <li><a href="#">Link B</a></li>
        </ul>
        <form class="navbar-form navbar-right" role="search">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Search">
          </div>
          <button type="submit" class="btn btn-default sr-only">Submit</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $rime_oauth_url; ?>">Sign in with Rime</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>