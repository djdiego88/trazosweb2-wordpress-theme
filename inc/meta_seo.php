<?php
global $seo_meta;
$seo_meta = array();

$seo_meta[] = array(
		"name" => "seo-description",
		"std" => "",
		"title" => __('Meta Description','tw2'),
		"description" => __('La Meta Description es un pequeño párrafo que describe de que trata una página o artículo en particular. Google y otros buscadores mostrarán esta descripción en los resultados de la búsqueda. Sin este texto, generalmente, los buscadores simplemente mostrarán pedazos relevantes de texto de tu sitio.','tw2'),
		"description2" => __('Ingresa una pequeña descripción para los buscadores.','tw2')
		);

$seo_meta[] =	array(
		"name" => "seo-keywords",
		"std" => "",
		"title" => __('Meta Keywords','tw2'),
		"description" => __('Las Meta keywords son palabras o frases que puedes usar para describir tu artículo o página. Definitivamente ahora no tienen el mismo impacto en el SEO como en el pasado, sin embargo estas todavía pueden ser útiles. De acuerdo a <a href=\"http://www.mattcutts.com/blog/\" target=\"_blank\">Matt Cutts</a>, responsable del buscador de Google, Google no utiliza las meta keywords para determinar las posiciones en sus resultados de búsqueda. Sin embargo, muchos otros buscadores si lo hacen. Así que, nunca es mala idea incluirlas.','tw2'),
		"description2" => __('Ingresa algunas palabras clave importantes. Separa estas palabras con comas.<br>Ej: palabra clave 1, palabra clave 2, palabra clave 3','tw2')
		);

function seo_meta() {

	global $post, $seo_meta;
	
	global $post_ID, $temp_ID;
	//print_r($seo_meta);

	foreach($seo_meta as $meta_box) {
		$meta_box_value = get_post_meta($post->ID, $meta_box['name'].'_value', true);
		
		//Si el campo esta vacío
		if($meta_box_value == "") {
		
			$meta_box_value = $meta_box['std'];
			
		}
			
		echo '<div class="post-meta">';
		echo '<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
		echo '<h2 style="margin:5px;">'.$meta_box['title'].' &nbsp;<a href="#help-' . $meta_box['name'] . '" class="tw2-open">'.__('¿Qué es esto?','tw2').'</a></h2>';
		
		//cuadro de Ayuda
		echo '<div id="help-' . $meta_box['name'] . '" class="help-box">';
		echo '<p>' . $meta_box['description'] . '</p>';
		echo '<p><a href="#help-' . $meta_box['name'] . '" class="tw2-close">'.__('Cerrar','tw2').'</a></p>';
		echo '</div>';
		
		echo '<p><textarea name="' . $meta_box['name'] . '_value" class="tw2-textarea" />' . $meta_box_value . '</textarea></p>';
		echo '<p>' . $meta_box['description2'] . '</p>';
		echo '</div>';

	}
}

function create_seo_meta() {
	global $theme_name;
	
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'seo-meta', __('Optimización para Motores de Búsqueda (SEO)','tw2'), 'seo_meta', 'post', 'normal', 'high' );
	}
	
	if ( function_exists('add_meta_box') ) {
		add_meta_box( 'seo-meta', __('Optimización para Motores de Búsqueda (SEO)','tw2'), 'seo_meta', 'page', 'normal', 'high' );
	}
}

function save_seo_meta( $post_id ) {
	global $post, $seo_meta;
	
	foreach($seo_meta as $meta_box) {
	// Verifica
		if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) )) {
			return $post_id;
		}
		
		if ( 'page' == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ))
				return $post_id;
			} else {
				if ( !current_user_can( 'edit_post', $post_id ))
				return $post_id;
		}
		
		$data = $_POST[$meta_box['name'].'_value'];
		
		if(get_post_meta($post_id, $meta_box['name'].'_value') == "")
			add_post_meta($post_id, $meta_box['name'].'_value', $data, true);
			
		elseif($data != get_post_meta($post_id, $meta_box['name'].'_value', true))
			update_post_meta($post_id, $meta_box['name'].'_value', $data);
			
		elseif($data == "")
			delete_post_meta($post_id, $meta_box['name'].'_value', get_post_meta($post_id, $meta_box['name'].'_value', true));
	}
}

function admin_register_head() {
	
	global $post;
	$template_url = get_bloginfo('template_url');
	
	//Incluye el CSS del Admin
	echo '<link rel="stylesheet" href="' . $template_url . '/css/admin.css" >';
	
	//Incluye el jQuery del Admin
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
	
	//Incluye el JS del Admin
	echo '<script src="' . $template_url . '/js/admin.js"></script>';
	
}

$seo = tw2_get_theme_seo();
if($seo['tw2_seo_meta'] != 0) {
	add_action('admin_head', 'admin_register_head');
	add_action('admin_menu', 'create_seo_meta');
}
add_action('save_post', 'save_seo_meta');
?>