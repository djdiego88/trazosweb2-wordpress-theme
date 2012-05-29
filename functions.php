<?php 
//Cargar varias opciones del theme
add_action('after_setup_theme', 'tw2_setup');
function tw2_setup(){
    //Cargar archivos de localización del theme
    load_theme_textdomain('tw2', get_template_directory() . '/lang');

    // Cargar las opciones del theme y código relacionado.
    require( dirname( __FILE__ ) . '/inc/theme-options.php' );
    require( dirname( __FILE__ ) . '/inc/meta_seo.php' );
    //Si no existe el plugin WP-Pagenavi incluye paginación personalizada
    if(!function_exists('wp_pagenavi')) { 
        require( dirname( __FILE__ ) . '/inc/pagenavi.php' );
    }
    
    // Añadir enlaces por defecto de RSS de artículos y comentarios al <head>.
    add_theme_support( 'automatic-feed-links' );

    // Añadir soporte para varios tipos de formatos de artículos
    add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

    /*Activa las miniaturas de WordPress*/
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(200,200,true);

    /*Elimina la versión de WordPress*/
    remove_action('wp_head', 'wp_generator');

    /*Elimina los mensajes de error de Ingreso*/
    add_filter('login_errors',create_function('$a', "return null;"));
}

// Comentarios personalizados

function custom_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
    <?php  if (get_comment_type() == "comment"){ // If you wanted to separate comments from pingbacks ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <article id="comment-<?php comment_ID(); ?>">
         <div class="avre">
         <div class="gravatar"><?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'], get_bloginfo('template_directory').'/img/noavatar.jpg' ); ?></div>
         <?php echo comment_reply_link(array('before' => '<p class="reply">', 'after' => '</p>', 'reply_text' => __('Responder','tw2'), 'depth' => $depth, 'max_depth' => $args['max_depth'] ));  ?>
         </div>
    	<div class="content">
            <?php
            printf( __( '<cite>%s</cite> Dice:', 'tw2' ), get_comment_author_link());
            ?>
            <?php if ($comment->comment_approved == '0') : ?>
            <em><?php _e('Tu comentario esta esperando moderación.', 'tw2'); ?></em>
            <?php endif; ?>
            <br />
            <span class="commentmetadata">
            <?php
            printf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
                esc_url( get_comment_link( $comment->comment_ID ) ),
                get_comment_time( 'c' ),
                sprintf( __( '%1$s a las %2$s', 'tw2' ), get_comment_date(__('F j, Y', 'tw2' )), get_comment_time(__('h:i A', 'tw2' )) )
              );
            ?>
             <?php edit_comment_link(__('Editar','tw2'),'',''); ?>
            </span>
            <?php comment_text() ?>
        </div>
    </article><!-- #comment-## -->    
    <?php    //The following are the pingback template. Will cause styling issues with odd and even styling due to threading.
    }  else { ?>
        <li <?php comment_class(); ?>><?php the_commenter_link() ?></li>
    <?php } 
}
function the_commenter_link() {
    $commenter = get_comment_author_link();
    if ( ereg( ']* class=[^>]+>', $commenter ) ) {$commenter = ereg_replace( '(]* class=[\'"]?)', '\\1url ' , $commenter );
    } else { $commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );}
    echo $commenter ;
}
function the_commenter_avatar() {
    $email = get_comment_author_email();
    $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( "$email", "32" ) );
    echo $avatar;
}

/* Poner el número de comentarios preciso */

add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
    if ( ! is_admin() ) {
        global $id;
        $comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}

/* Obtener número Followers en Twitter*/

function getFollowers($username){
    $content = file_get_contents("http://api.twitter.com/1/users/show.json?screen_name=".$username);
    $followers_count = json_decode($content);
    return $followers_count->{'followers_count'};
}

/* Obtener número de suscriptores en Feedburner */

function getFeedburner($rssurl){
$fburl="https://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=".$rssurl;
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $fburl);
$stored = curl_exec($ch);
curl_close($ch);
$grid = new SimpleXMLElement($stored);
$rsscount = $grid->feed->entry['circulation'];
return $rsscount;
}

/* Cuenta de likes en facebook de la página del blog */

function fb_like_count($username) {
$content = file_get_contents("http://graph.facebook.com/".$username."/");
$fb_likes_count = json_decode($content);
return $fb_likes_count->{'likes'};
}

/* Enlace de la página de facebook del blog */

function fb_link_profile($username) {
$content = file_get_contents("http://graph.facebook.com/".$username."/");
$fb_likes_count = json_decode($content);
return $fb_likes_count->{'link'};
}

/* ID de la página de facebook del blog */

function fb_id_profile() {
$social = tw2_get_theme_social();
$username = $social['tw2_facebook_page'];
if($username == '')
    return;
$content = file_get_contents("http://graph.facebook.com/".$username."/");
$fb_likes_count = json_decode($content);
return $fb_likes_count->{'id'};
}

/* Poner el rel_canonical a los comentarios */

function canonical_for_comments() {
	global $cpage, $post;
	if ( $cpage > 1 ) :
		echo "\n";
	  	echo "<link rel='canonical' href='";
	  	echo get_permalink( $post->ID );
	  	echo "' />\n";
	 endif;
}
add_action( 'wp_head', 'canonical_for_comments' );

/* Regresa un enlace "Continúa Leyendo para los extractos */

function tw2_continue_reading_link() {
    return ' <a href="'. esc_url( get_permalink() ) . '" class="more-link">' . __( 'Continúa Leyendo', 'tw2' ) . '</a>';
}

/* Añadir ellipsis y el enlace del extracto */

function tw2_auto_excerpt_more( $more ) {
    return ' &hellip;' . tw2_continue_reading_link();
}
add_filter( 'excerpt_more', 'tw2_auto_excerpt_more' );

/* Añade un enñace al extracto */

function tw2_custom_excerpt_more( $output ) {
    if ( has_excerpt() && ! is_attachment() ) {
        $output .= tw2_continue_reading_link();
    }
    return $output;
}
add_filter( 'get_the_excerpt', 'tw2_custom_excerpt_more' );

/* Registrar áreas de sidebars y widgets */

function tw2_widgets_init() {
    register_sidebar(array(
		'name' => __('Sidebar Arriba','tw2'),
        'id' => 'sidebar-1',
        'before_widget' => '<aside class="sbb clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
		'name' => __('Sidebar  al Fondo','tw2'),
        'id' => 'sidebar-2',
        'before_widget' => '<aside class="sbb clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer a la Izquierda','tw2'),
        'id' => 'sidebar-3',
        'description' => __( 'Espacio para mostrar el blogroll. Máximo 10 enlaces por columna.', 'tw2' ),
        'before_widget' => '<aside class="fb clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer en medio','tw2'),
        'id' => 'sidebar-4',
        'description' => __( 'Espacio para mostrar el listado de categorías. Máximo 10 enlaces por columna.', 'tw2' ),
        'before_widget' => '<aside class="cats clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer a la Derecha','tw2'),
        'id' => 'sidebar-5',
        'before_widget' => '<aside class="bot clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}
add_action( 'widgets_init', 'tw2_widgets_init' );

/*Insertar contenido en el footer del Feed RSS*/

function tw2_postrss($content) {
    if(is_feed()){
    global $wp_query;
    $postid = $wp_query->post->ID;
    $social = tw2_get_theme_social();
        if($social['tw2_postrss'] != ''){
            $strings = array("[[posturl]]","[[permalink]]", "[[posttitle]]", "[[bloglink]]", "[[actualyear]]", "[[blogname]]", "[[blogdescription]]", "[[blogurl]]");
            $replace = array(get_permalink($postid), "<a title=\"".get_the_title($postid)."\" href=\"".get_permalink($postid)."\" rel=\"bookmark\">".get_the_title($postid)."</a>", get_the_title($postid), "<a href=\"".get_bloginfo('home')."/\" title=\"".get_bloginfo('description')."\">".get_bloginfo('name')."</a>", gmdate(__('Y')), get_bloginfo('name'), get_bloginfo('description'), get_bloginfo('home'));
            $postrss = str_replace($strings , $replace , $social['tw2_postrss']);	
            $content = $content.'<br /><hr />'.$postrss;
        }
    }
    return $content;
}
add_filter('the_excerpt_rss', 'tw2_postrss');
add_filter('the_content', 'tw2_postrss');	

/*Redirecciona todos los RSS a Feedburner*/

function custom_feed_link($output, $feed) {
    $social = tw2_get_theme_social();
    if($social['tw2_feedburner'] != '' && $social['tw2_redirect_rss'] != 0){
        $feed_url = $social['tw2_feedburner'];
        $feed_array = array('rss' => $feed_url, 'rss2' => $feed_url, 'atom' => $feed_url, 'rdf' => $feed_url, 'comments_rss2' => '');
        $feed_array[$feed] = $feed_url;
        $output = $feed_array[$feed];
    }
return $output;
}
function other_feed_links($link) {
    $social = tw2_get_theme_social();
    if($social['tw2_feedburner'] != '' && $social['tw2_redirect_rss'] != 0){
        $link = $social['tw2_feedburner'];
    }
return $link;
}
//Añadir nuestras funciones a filtros específicos
add_filter('feed_link','custom_feed_link', 1, 2);
add_filter('category_feed_link', 'other_feed_links');
add_filter('author_feed_link', 'other_feed_links');
add_filter('tag_feed_link','other_feed_links');
add_filter('search_feed_link','other_feed_links');	

/*Quitar enlaces automáticos en comentarios*/

remove_filter('comment_text', 'make_clickable', 9);

/* Disable the Admin Bar. */

add_filter( 'show_admin_bar', '__return_false' );

/* Remove the Admin Bar preference in user profile */

remove_action( 'personal_options', '_admin_bar_preferences' );

/*Breadcrumbs*/

function dimox_breadcrumbs() {

  $delimiter = __('&raquo;','tw2');
  $home = __('Inicio','tw2'); // text para el enlace de 'Inicio'
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb

  if ( !is_home() && !is_front_page() || is_paged() ) {
    echo '<div id="crumbs">';
    global $post;
    $homeLink = get_bloginfo('url');
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

    if ( is_category() ) {

      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before;
      printf(__('Archivo por categoría "%s"','tw2'),single_cat_title('', false));
      echo $after;

    } elseif ( is_day() ) {

      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {

      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {

      echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {

      if ( get_post_type() != 'post' ) {

        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;

      } else {

        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;

      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {

      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {

      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {

      echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {

      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;

    } elseif ( is_search() ) {

      echo $before;
      printf(__('Resultados de la búsqueda para "%s"','tw2'),get_search_query());
      echo $after;

    } elseif ( is_tag() ) {

      echo $before;
      printf(__('Artículos etiquetados en "%s"','tw2'),single_tag_title('', false));
      echo $after;

    } elseif ( is_author() ) {

       global $author;
      $userdata = get_userdata($author);
      echo $before;
      printf(__('Artículos publicados por %s','tw2'),$userdata->display_name);
      echo $after;

    } elseif ( is_404() ) {

      echo $before . __('Error 404','tw2') . $after;

    }

    if ( get_query_var('paged') ) {

      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Página', 'tw2') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';

    }

    echo '</div>';
  }

} // end dimox_breadcrumbs()

/*Buscar la primera imagen del artículos y si no existe poner imagen por defecto*/

function catch_that_image() {
 global $post, $posts;
 $first_img = '';
 ob_start();
 ob_end_clean();
 $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
 $first_img = $matches [1] [0];
 if(empty($first_img)){ //Defines a default image
    $social = tw2_get_theme_social();
    if($social['tw2_default_image'] != ''){ 
      $first_img = $social['tw2_default_image'];
    }else{ 
      $first_img = get_template_directory()."/img/default.png";
    }
 }
 return $first_img;
}

/* Sacar un extracto del contenido del artículo */

function og_meta_desc() {
 global $post;
 $meta = strip_tags($post->post_content);
 $meta = str_replace(array("\n", "\r", "\t"), ' ', $meta);
 $meta = substr($meta, 0, 200);
 echo "<meta property=\"og:description\" content=\"$meta\">";
}

/* Imprimir los datos de Open Social de Facebook en el header */

function og_facebook(){
    if (is_single()) {
    global $post;
    $social = tw2_get_theme_social();
    ?>
        <meta property="og:title" content="<?php the_title(); ?>">
        <meta property="og:type" content="article" />
        <meta property="og:image" content="<?php echo catch_that_image(); ?>">
        <meta property="og:url" content="<?php the_permalink(); ?>" />
        <?php og_meta_desc(); ?>
    <?php   } else { ?>
        <meta property="og:title" content="<?php bloginfo('name'); ?>&nbsp;|&nbsp;<?php bloginfo('description'); ?>">
        <meta property="og:type" content="blog" />
        <?php if($social['tw2_default_image'] != ''){ ?>
            <meta property="og:image" content="<?php echo $social['tw2_default_image']; ?>">
        <?php }else{ ?>
            <meta property="og:image" content="<?php bloginfo('template_directory'); ?>/img/default.png">
        <?php } ?>
        <meta property="og:url" content="<?php bloginfo('home'); ?>">
        <meta property="og:description" content="<?php bloginfo('description'); ?>">
    <?php   } ?>
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>">
    <?php if($social['tw2_facebook_page']){ ?>
        <meta property="fb:page_id" content="<?php echo fb_id_profile(); ?>">
    <?php } ?>
<?php 
}

/* Imprimir los datos como fecha, autor y comentarios del artículo */

function tw2_posted_on() {

    printf( __( '<li>Publicado por: <strong><a href="%1$s" title="%2$s" rel="author">%3$s</a></strong> el </li><li> <time class="entry-date" datetime="%4$s" pubdate>%5$s</time> </li><li> en %6$s </li>', 'tw2' ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        sprintf( esc_attr__( 'Ver todos los artículos de %s', 'tw2' ), get_the_author() ),
        esc_html( get_the_author() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( mysql2date(__('j \d\e F \d\e Y', 'tw2'),get_the_date('Y-m-d H:i:s'))),
        get_the_category_list(', ')
    );

}

/* Muestra el Feed RSS del blog */

function feedburner(){ 
    $social = tw2_get_theme_social();
    if ($social['tw2_feedburner'] != '') {
        echo $social['tw2_feedburner'];
    }
    else {
        echo get_bloginfo('rss2_url');
    }
}
/* Muestra el Favicon elegido para el blog */
function favicon_url(){ 
    $general = tw2_get_theme_general();
    if ($general['tw2_favicon'] != '') {
        echo $general['tw2_favicon'];
    }
    else {
        echo get_bloginfo('stylesheet_directory') ."/img/favicon.ico";
    }
}

?>