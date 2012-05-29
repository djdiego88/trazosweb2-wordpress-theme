<?php get_header(); ?>
<div id="content" role="main">
<?php $general = tw2_get_theme_general(); if (function_exists('dimox_breadcrumbs') && $general['tw2_breadcrumbs'] != 0) dimox_breadcrumbs(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- google_ad_section_start -->
	<header class="entry-header">
		<h1 class="title"><?php the_title(); ?></h1>
	</header>
	<div class="post_inner_wrap clearfix">
	<?php the_content(__('Continúa leyendo', 'tw2')); ?>
	<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Páginas:', 'tw2' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!--/post_inner_wrap-->
	<!-- google_ad_section_end -->
</article><!--/post-->
<?php endwhile; endif; ?>
</div><!--/content-->
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>