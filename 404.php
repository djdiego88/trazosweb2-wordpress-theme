<?php get_header(); ?>
<div id="content" role="main">
<?php $general = tw2_get_theme_general(); if (function_exists('dimox_breadcrumbs') && $general['tw2_breadcrumbs'] != 0) dimox_breadcrumbs(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="post" id="post-<?php the_ID(); ?>">
<h1 class="title"><?php the_title(); ?></h1>
<div class="post_inner_wrap clearfix">
<div id="error_404" align="center"></div>
<span><?php printf(__('Parece que has tomado un camino equivocado. Prueba buscando otro término en el formulario de búsqueda o  puedes ir a la <a href="%s">página de Inicio</a>, puedes elegir lo que desees en la barra lateral o puedes ir a alguna de las secciones que te muestro a continuación.','tw2'), get_bloginfo('home'));?></span><br /><br />
<strong><?php _e('Búsqueda por páginas','tw2');?>:</strong>
<ul>
<?php wp_list_pages('title_li='); ?>
</ul>
<strong><?php _e('Búsqueda por Categorías','tw2');?>:</strong>
<ul>
<?php wp_list_cats('sort_column=name'); ?>
</ul>
</div><!--/post_inner_wrap-->
</div><!--/post-->
<?php endwhile; endif; ?>
</div><!--/content-->
<?php get_sidebar(); ?>
<div class="clear"></div>
<?php get_footer(); ?>