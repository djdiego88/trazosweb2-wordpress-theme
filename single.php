<?php get_header(); 
$general = tw2_get_theme_general();
$social = tw2_get_theme_social();
?>
<div id="content" role="main">
<?php if (function_exists('dimox_breadcrumbs') && $general['tw2_breadcrumbs'] != 0) dimox_breadcrumbs(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- google_ad_section_start -->
	<header class="entry-header">
		<h1 class="title"><?php the_title(); ?></h1>
		<ul class="the_post_meta"><?php tw2_posted_on();?><li><span class="right ncomments"><?php comments_popup_link(__('0 Comentarios', 'tw2'), __('1 Comentario', 'tw2'), _n('% Comentario', '% Comentarios', get_comments_number(),'tw2')); ?></span></li></ul><!--/the_post_meta-->
	</header><!-- .entry-header -->
	<div class="post_inner_wrap clearfix">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Páginas:', 'tw2' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!--/post_inner_wrap-->
	<!-- google_ad_section_end -->
	<footer class="entry-footer">
		<div class="di"></div>
		<?php the_tags( '<div class="the_tags">' . __( 'Tags', 'tw2' ) . ': ', ', ', '</div>'); ?><!--/the_tags-->
		<div class="di"></div>
		<div id="post_footer">
			<div class="rss"><a href="<?php feedburner(); ?>"><?php printf(__('Suscríbete al Feed RSS de %s', 'tw2'), get_bloginfo('name')); ?></a></div><!--/rss-->
			<div class="share">
				<ul class="social">
				<li class="facebook"><a href="http://www.facebook.com/sharer.php?u=<?php urlencode(the_permalink()); ?>&amp;t=<?php urlencode(the_title()); ?>" title="<?php _e('Comparte este artículo en Facebook', 'tw2'); ?>" target="_blank" rel="nofollow">Facebook</a></li>
				<li class="delicious"><a href="http://delicious.com/post?url=<?php urlencode(the_permalink()); ?>&amp;title=<?php urlencode(the_title()); ?>" title="<?php _e('Marca este artículo en Delicious', 'tw2'); ?>" target="_blank" rel="nofollow">Delicious</a></li>
				<li class="stumbleupon"><a href="http://www.stumbleupon.com/submit?url=<?php urlencode(the_permalink()); ?>&amp;title=<?php urlencode(the_title()); ?>" title="<?php _e('Comparte este artículo en StumbleUpon', 'tw2'); ?>" target="_blank" rel="nofollow">StumbleUpon</a></li>
				<li class="technorati"><a href="http://www.technorati.com/faves?add=<?php urlencode(the_permalink()); ?>" title="<?php _e('Comparte este artículo en Technorati', 'tw2'); ?>" target="_blank" rel="nofollow">Technorati</a></li>
				<li class="google"><a href="http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=<?php urlencode(the_permalink()); ?>&amp;title=<?php urlencode(the_title()); ?>" title="<?php _e('Comparte este artículo en Google Bookmark', 'tw2'); ?>" target="_blank" rel="nofollow">Google Bookmark</a></li>
				<li class="email"><a href="http://www.feedburner.com/fb/a/emailFlare?itemTitle=<?php urlencode(the_title()); ?>&amp;uri=<?php urlencode(the_permalink()); ?>&amp;loc=es_ES" title="<?php _e('Envíale este artículo a un amigo', 'tw2'); ?>" target="_blank" rel="nofollow">Email</a></li>
				</ul><!--/social-->
			</div><!--/share-->
			<div id="facebooklike"><div class="fb-like" data-href="<?php urlencode(the_permalink()); ?>" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false" data-font="arial"></div></div><!--/facebooklike-->
			<?php if($social['tw2_twitter_account'] != '') { ?>
			<div id="twitshare"><a href="https://twitter.com/share" class="twitter-share-button" data-via="<?php echo $social['tw2_twitter_account']; ?>"><?php _e('Twittear', 'tw2'); ?></a></div><!--/twitshare-->
			<?php }else{ ?>
			<div id="twitshare"><a href="http://twitter.com/share" class="twitter-share-button"><?php _e('Twittear', 'tw2'); ?></a></div><!--/twitshare-->	
			<?php } ?>
			<div id="plusbutton"><g:plusone size="medium"></g:plusone></div><!--plusbutton-->
		</div><!--/post_footer-->
		<div id="related_articles">
		<h3><?php _e('Artículos Relacionados', 'tw2'); ?></h3>
		<!-- google_ad_section_start -->
		<?php
		//Produce una lista de artículos relacionados con las etiquetas del artículo
		  $tags = wp_get_post_tags($post->ID);
		  $tagIDs = array();
		  if ($tags) {
		    $tagcount = count($tags);
		    for ($i = 0; $i < $tagcount; $i++) {
		      $tagIDs[$i] = $tags[$i]->term_id;
		    }
			if($general['tw2_related_posts'] != ''){$relposts = $general['tw2_related_posts'];}else{$relposts = 5;}
		    $args=array( 'tag__in' => $tagIDs, 'post__not_in' => array($post->ID), 'showposts'=>$relposts, 'caller_get_posts'=>1 );
		  $my_query = new WP_Query($args);
		    if( $my_query->have_posts() ) {?>
		    <ul>
		    <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		      <li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><div class="title"><?php the_title(); ?></div></a></li>
		       <?php endwhile;?>
		     </ul>
		    <?php } else { 
		    _e('No hay artículos relacionados');
		     } wp_reset_query(); } ?>
		<!-- google_ad_section_end -->
		</div><!--/related articles-->
	</footer><!--.entry-footer-->
	<div class="divisor"></div>
	<div id="comments">
	<?php comments_template('', true); ?>
	</div><!--/comments-->
</article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; endif; ?>
</div><!--/content-->
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>