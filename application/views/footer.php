<footer>
  <div class="container">
    <div class="row">
      <div class="col-xs-12">
        <div class="credits text-center"><?php echo anchor('http://www.idc.iitb.ac.in/', 'IDC - IITB'); ?> and <?php echo anchor('http://rime.co/', 'Rime'); ?></div>
      </div>
    </div>
  </div>
</footer>

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

<!-- add google analytics !-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47952562-1', 'dsquare.in');
  ga('send', 'pageview');
</script>