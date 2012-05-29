</div><!--/content-wrapper-->
<footer id="footer">
<?php if ( ! dynamic_sidebar( 'sidebar-3' ) ) : ?>
	<aside class="fb clearfix">
		<h3><?php _e('Blogroll', 'tw2') ?></h3>
		<ul>
			<?php wp_list_bookmarks('title_li=&categorize=0&limit=10'); ?>
		</ul>
	</aside><!--/fb-->
<?php endif; // end sidebar widget area ?>
<?php if ( ! dynamic_sidebar( 'sidebar-4' ) ) : ?>
<aside class="cats clearfix">
	<h3><?php _e('Categorías', 'tw2') ?></h3>
	<?php
	$get_cats = wp_list_categories( 'echo=0&title_li=&depth=1&hide_empty=0&number=20' );
	$cat_array = explode('</li>',$get_cats);
	$results_total = count($cat_array);
	$cats_per_list = ceil($results_total / 2);
	$list_number = 1;
	$result_number = 0;
	?>
	<!-- google_ad_section_start -->
	<ul class="cat_col" id="cat-col-<?php echo $list_number; ?>">
	<?php
	foreach($cat_array as $category) {
		$result_number++;

		if($result_number % $cats_per_list == 0) {
			$list_number++;
			echo $category.'</li>
			</ul>
			<ul class="cat_col" id="cat-col-'.$list_number.'">';
		}
		else {
			echo $category.'</li>';
		}
	}
	?>
	</ul>
	<!-- google_ad_section_end -->
</aside><!--/cats-->
<?php endif; // end sidebar widget area ?>
<?php if ( ! dynamic_sidebar( 'sidebar-5' ) ) : ?>
<aside class="bot clearfix">
</aside><!--/bot-->
<?php endif; // end sidebar widget area ?>
</footer><!--/footer-->
</div><!--/main-->
<div id="footer-bt"></div><!--/footer-bt-->
<div id="copyright">
<?php 
$general = tw2_get_theme_general(); ?>
<p class="left"><?php if($general['tw2_footer_copyright'] != '') { ?><?php echo $general['tw2_footer_copyright']; ?><?php } ?> | <a href="http://wordpress.org/" target="_blank" rel="nofollow"><?php printf(__('Creado con %s','tw2'),'WordPress');?></a></p> <p class="right"><?php _e('Diseñado por', 'tw2'); ?> &nbsp;&nbsp;<a class="logof" href="http://www.trazos-web.com" title="<?php printf(__('%s - Blogging, Desarrollo y Diseño Web','tw2'),'Trazos Web');?>"><img src="<?php bloginfo('template_directory'); ?>/img/footer_logo.png" alt="<?php printf(__('%s - Blogging, Desarrollo y Diseño Web','tw2'),'Trazos Web');?>" width="111px" height="16px"></a></p>
</div><!--/copyright-->
</div><!--/wrapper-->
<!-- Beautiful design by Diego Castillo - http://www.trazos-web.com -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js">\x3C/script>')</script>
<script src="<?php bloginfo('template_directory'); ?>/js/tabs.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.qtip.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.lazyload.mini.js"></script>
<script>
// Create the tooltips only on document load
$(document).on('ready',function() {
	$('#botones a[title]').qtip({position: {my: 'top right',  at: 'bottom left', target: 'mouse'},show: {event: 'mouseenter mouseover'},hide: {event: 'mouseleave mouseout'},style: {classes: 'ui-tooltip-light ui-tooltip-rounded'}});
	if (navigator.platform == "iPad") return;
		$("#content img").lazyload({
			effect:"fadeIn",
			placeholder: "<?php bloginfo('template_directory'); ?>/img/grey.gif"
		});
});
</script>
<!-- Facebook Script -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Twitter Script -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<!-- Google+ Script -->
<script type="text/javascript">
  window.___gcfg = {lang: 'es-419'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<!-- Analytics -->
<?php if($general['tw2_google_analytics'] != '') { ?>
<script>
    var _gaq=[['_setAccount','<?php echo $general['tw2_google_analytics']; ?>'],['_trackPageview'],['_trackPageLoadTime']];
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>
<?php } ?>
<?php if($general['tw2_analytics'] != '') { echo stripslashes($general['tw2_analytics']); } ?>
<!--/Analytics -->
<?php wp_footer(); ?>
</body>
</html>
