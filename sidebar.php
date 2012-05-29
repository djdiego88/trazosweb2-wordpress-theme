<div id="sidebar" role="complementary">
<?php $ads = tw2_get_theme_ads(); if ($ads['tw2_ads125'] != 0) { ?>
    <div class="ads clearfix">
    <?php 
		$number = $ads['tw2_ads_number'];
		if ($number == 0) $number = 1;
		$alt_img = array();
		$img_url = array();
		$dest_url = array();
		$numbers = range(1,$number); 
		$counter = 0;
		if ($ads['tw2_ads_rotate'] != 0) {
		shuffle($numbers);
		}
	?>
     <h3><?php _e( 'Patrocinadores', 'tw2' ); ?></h3>
       <div class="adsbanners">
         <?php
			foreach ($numbers as $number) {	
			$counter++;
			$alt_img[$counter] = $ads['tw2_ad_alt_'.$number];
			$img_url[$counter] = $ads['tw2_ad_image_'.$number];
			$dest_url[$counter] = $ads['tw2_ad_url_'.$number];
		?>
        <a href="<?php echo "$dest_url[$counter]"; ?>" title="<?php echo "$alt_img[$counter]"; ?>"><img src="<?php echo "$img_url[$counter]"; ?>" alt="<?php echo "$alt_img[$counter]"; ?>" width="125" height="125" /></a>
        <?php } ?>
       </div>
    </div><!--/ads-->
<?php } ?>

<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

<?php endif; ?>

<div class="tabs clearfix">
<!-- google_ad_section_start -->
<div class="box">
<ul id="tabMenu">
<li class="famous selected" title="<?php _e( 'Artículos Populares', 'tw2' ); ?>"></li>
<li class="commentst" title="<?php _e( 'Últimos Comentarios', 'tw2' ); ?>"></li>
<li class="category" title="<?php _e( 'Etiquetas', 'tw2' ); ?>"></li>
<li class="random" title="<?php _e( 'Artículos Aleatorios', 'tw2' ); ?>"></li>
<li class="posts" title="<?php _e( 'Artículos Recientes', 'tw2' ); ?>"></li>
</ul>
<div class="boxTop"></div>
<div class="clear"></div>
<div class="boxBody round_8">
<div id="famous" class="show">
<h3><?php _e( 'Artículos Populares', 'tw2' ); ?></h3>
<ul>
	<?php 
$request = "SELECT comment_count,ID,post_title FROM $wpdb->posts";
$request .= " WHERE post_date > '" . date('Y-m-d', strtotime('-430 days')) . "'";
$request .= " ORDER BY comment_count DESC LIMIT 0 , 10";
$result = $wpdb->get_results($request);
    foreach ($result as $post) {
    setup_postdata($post);
    $postid = $post->ID;
    $title = $post->post_title;
    $commentcount = $post->comment_count;
    if ($commentcount != 0) { ?>
    <li><a href="<?php echo get_permalink($postid); ?>" title="<?php printf( esc_attr__('Enlace Permanente a %s', 'tw2' ), $title ); ?>">
    <?php echo $title ?></a></li>
    <?php } } ?>
    </ul>
</div>  
<div id="commentst">
<h3><?php _e( 'Últimos Comentarios', 'tw2' ); ?></h3>
<ul>
      <?php
		global $wpdb;
		$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID,
		comment_post_ID, comment_author, comment_date_gmt, comment_approved,
		comment_type,comment_author_url,
		SUBSTRING(comment_content,1,50) AS com_excerpt
		FROM $wpdb->comments
		LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID =
		$wpdb->posts.ID)
		WHERE comment_approved = '1' AND comment_type = '' AND
		post_password = ''
		ORDER BY comment_date_gmt DESC LIMIT 5";
		$comments = $wpdb->get_results($sql);
		$output = $pre_HTML;
		foreach ($comments as $comment) {
		$output .= "\n
		<li>"."<a href=\"" . get_permalink($comment->ID) .
		"#comment-" . $comment->comment_ID . "\" title=\"" .
		$comment->post_title . "\">" .strip_tags($comment->com_excerpt)
		."... <span>" . strip_tags($comment->comment_author)
		.".</span></a></li>
		";
		}
		$output .= $post_HTML;
		echo $output; ?>
</ul>
</div>
<div id="category">
<h3><?php _e( 'Etiquetas', 'tw2' ); ?></h3>
<?php wp_tag_cloud('smallest=10&largest=18'); ?>
</div>
<div id="random">
<h3><?php _e( 'Artículos Aleatorios', 'tw2' ); ?></h3>
<ul>
      <?php
	  query_posts(array('orderby' => 'rand', 'showposts' => 10));
	  if (have_posts()) :
	  while (have_posts()) : the_post();
	  ?>
	  <li><a href="<?php echo the_permalink(); ?>" title="<?php printf( esc_attr__('Enlace Permanente a %s', 'tw2' ), the_title_attribute( 'echo=0' ) ); ?>"><?php echo the_title() ?></a></li>
	  <?php endwhile;
	  endif; ?>
</ul>
</div>
<div id="posts">
<h3><?php _e( 'Artículos Recientes', 'tw2' ); ?></h3>
<ul>
      <?php
	  query_posts(array('orderby' => 'post_date', 'order' => 'desc', 'showposts' => 10));
	  if (have_posts()) :
	  while (have_posts()) : the_post();
	  ?>
	  <li><a href="<?php echo the_permalink(); ?>" title="<?php printf( esc_attr__('Enlace Permanente a %s', 'tw2' ), the_title_attribute( 'echo=0' ) ); ?>"><?php echo the_title() ?></a></li>
	  <?php endwhile;
	  endif; ?>
</ul>   
</div>        
</div>
<div class="boxBottom"></div>
</div>
<!-- google_ad_section_end -->
</div><!--/tabs-->
<?php if ( ! dynamic_sidebar( 'sidebar-2' ) ) : ?>
<?php endif; ?>
</div><!--/sidebar-->