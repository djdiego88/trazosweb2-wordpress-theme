<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<?php og_facebook(); ?>
<title>
<?php
global $page, $paged;
wp_title( '|', true, 'right' );
bloginfo( 'name' );
$site_description = get_bloginfo( 'description', 'display' );
if ( $site_description && ( is_home() || is_front_page() ) )
    echo " | $site_description";
// Añade número de página si es necesario:
if ( $paged >= 2 || $page >= 2 )
    echo ' | ' . sprintf( __( 'Página %s', 'tw2' ), max( $paged, $page ) );
?>
</title>
<?php
    $general = tw2_get_theme_general();
    $social = tw2_get_theme_social();
    $seo = tw2_get_theme_seo();
    //SEO meta info
    if( is_home() ) {
        //Muestra la meta description si existe
        if($seo['tw2_home_description'] != '') {
            echo "<meta name=\"description\" content=\"" . $seo['tw2_home_description'] . "\">\n";
        }
        //Muestra la meta keywords si existe
        if($seo['tw2_home_keywords'] != '') {
            echo "<meta name=\"keywords\" content=\"" . $seo['tw2_home_keywords'] . "\">\n";
        }
    } else {
        //Si el SEO en las páginas y artículos es seleccionado...
        if($seo['tw2_seo_meta'] != 0) {
            //Muestra la meta description si existe
            $meta_description = get_post_meta($post->ID, 'seo-description_value', true);
            if($meta_description) {
                echo "<meta name=\"description\" content=\"" . $meta_description . "\">\n";
            }
            //Muestra la meta keywords si existe
            $meta_keywords = get_post_meta($post->ID, 'seo-keywords_value', true);
            if($meta_keywords) {
                echo "<meta name=\"keywords\" content=\"" . $meta_keywords . "\">\n";
            }
        }
    }
?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href='http://fonts.googleapis.com/css?family=Ubuntu:bold' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" media="screen">
<!--CSS dependiendo del Estilo escogido-->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/<?php if($general['tw2_theme_style'] != '') { echo $general['tw2_theme_style']; } else { echo "style1"; } ?>.css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jquery.qtip.min.css" media="screen">
<link rel="alternate" title="RSS 2.0" href="<?php feedburner(); ?>">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php favicon_url(); ?>">
<script src="<?php bloginfo('template_directory'); ?>/js/modernizr.js"></script>
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<?php flush(); ?>
<!-- Beautiful design by Diego Castillo - http://www.trazos-web.com -->
<body <?php body_class(); ?>>
<div id="wrapper">
<header>
<div id="logo">
<?php if($general['tw2_logo'] != ''){ ?>
<a class="logo" href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('description'); ?>"><img src="<?php echo $general['tw2_logo']; ?>" width="384" height="72" alt="<?php bloginfo('name'); ?>"></a>
<?php }else{ ?>
<h1><a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('description'); ?>"><?php bloginfo('name'); ?></a></h1>
<h2><?php bloginfo('description'); ?></h2>    
<?php } ?>
</div><!--/logo-->
<div id="botones">
<ul class="socials">
<?php if($social['tw2_facebook_page'] != '') { ?>
<li class="bface"><a href="<?php echo fb_link_profile($social['tw2_facebook_page']); ?>" target="_blank" title="<?php printf(__('Házte fan de %s en Facebook', 'tw2'), get_bloginfo('name')); ?>" rel="nofollow">Facebook</a></li>
<?php } ?>
<?php if($social['tw2_twitter_account'] != '') { ?>
<li class="btwitter"><a href="http://twitter.com/<?php echo $social['tw2_twitter_account']; ?>" target="_blank" title="<?php printf(__('Sigue a %s en Twitter', 'tw2'), get_bloginfo('name')); ?>" rel="nofollow">Twitter</a></li>
<?php } ?>
<li class="brss"><a href="<?php feedburner(); ?>" title="<?php printf(__('Suscríbete a %s mediante RSS', 'tw2'), get_bloginfo('name')); ?>" rel="nofollow">Rss</a></li>
<?php if($social['tw2_email_subscription'] != '') { ?>
<li class="bemail"><a href="<?php echo $social['tw2_email_subscription']; ?>" target="_blank" title="<?php printf(__('Recibe los últimos artículos de %s en tu E-mail', 'tw2'), get_bloginfo('name')); ?>" rel="nofollow">Email</a></li>
<?php } ?>
<?php if($social['tw2_gplus_page'] != '') { ?>
<li class="bgplus"><a href="<?php echo $social['tw2_gplus_page']; ?>" target="_blank" title="<?php printf(__('Súscríbete a la página de %s en Google Plus', 'tw2'), get_bloginfo('name')); ?>" rel="nofollow">Facebook</a></li>
<?php } ?>
</ul>
</div><!--/botones-->
<div class="clear"></div>

<div id="menu-wrapper">
<div id="menu">
<nav><ul id="navigation">
<li <?php if ( is_home() ) { ?> class="current_page_item" <?php } ?>><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Inicio', 'tw2' ); ?></a></li>
<?php wp_list_pages('sort_column=menu_order&title_li=&depth=1'); ?>
</ul></nav><!--/navigation-->
<div id="search">
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <div>
    <input class="search_input" type="search" name="s" id="s" placeholder="<?php esc_attr_e( 'Ingresa aquí tu búsqueda', 'tw2' ); ?>" required>
    <input class="search_button" type="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Buscar', 'tw2' ); ?>"> 
  </div>
</form>
</div><!--/search-->
</div><!--/menu-->
<div id="categories_bar">
<ul>
<?php wp_list_categories('title_li=&number=12'); ?>
</ul>
</div><!--/categories_bar-->
</div><!--/menu-wrapper-->
</header><!--/header-->
<div id="main">
<div class="white"></div>
<div id="content-wrapper">