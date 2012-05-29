<?php get_header(); ?>
<div id="content" role="main">
<?php $general = tw2_get_theme_general(); if (function_exists('dimox_breadcrumbs') && $general['tw2_breadcrumbs'] != 0) dimox_breadcrumbs(); ?>
<?php
	if(isset($_GET['author_name'])) :
	$curauth = get_userdatabylogin($author_name);
	else :
	$curauth = get_userdata(intval($author));
	endif;
?>
<?php if (have_posts()) : ?>
<h3><?php printf( __( 'Artículos del Autor: <em>%s</em>', 'tw2' ), $curauth->display_name ); ?></h3>
<dl>
  <dt><b><?php _e('Website', 'tw2'); ?></b></dt>
  <dd><a href="<?php echo $curauth->user_url; ?>"><?php echo $curauth->user_url; ?></a></dd>
  <dt><b><?php _e('Perfil', 'tw2'); ?></b></dt>
  <dd><?php echo $curauth->user_description; ?></dd>
</dl>
<div class="divisor"></div>
<?php while (have_posts()) : the_post(); ?>	
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- google_ad_section_start -->
	<header class="entry-header">
		<h1 class="title"><a title="<?php printf( esc_attr__('Enlace Permanente a %s', 'tw2' ), the_title_attribute( 'echo=0' ) ); ?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<ul class="the_post_meta"><?php tw2_posted_on();?><li><span class="right ncomments"><?php comments_popup_link(__('0 Comentarios', 'tw2'), __('1 Comentario', 'tw2'), _n('% Comentario', '% Comentarios', get_comments_number(),'tw2')); ?></span></li></ul><!--/the_post_meta-->
	</header>
	<div class="post_inner_wrap clearfix">
	<?php the_content(__('Continúa leyendo', 'tw2')); ?>
	</div><!--/post_inner_wrap-->
	<!-- google_ad_section_end -->
</article><!--/post-->
<div class="divisor"></div>
<?php endwhile; ?>
<div class="pagination clearfix" align="center">
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
			else if(function_exists('pagenavi')) { pagenavi(); } ?>
</div><!--Termina pagination-->
<?php else : ?>
<h2 class="center"><?php _e( 'No se ha encontrado', 'tw2' ); ?></h2>
<p class="center"><?php _e( 'Disculpa, pero estás buscando algo que no se encuentra aquí.', 'tw2' ); ?></p>
<?php endif; ?>
</div><!--/content-->
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>