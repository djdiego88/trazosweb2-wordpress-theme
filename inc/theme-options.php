<?php

/* Registrar la configuración del formulario para el array de tw2_options */

function tw2_theme_options_init() {
	// Si no existen las opciones en la base de datos, las añadimos ahora.
	if ( false === tw2_get_theme_general() )
		add_option( 'tw2_theme_general', tw2_get_default_theme_general() );
	if ( false === tw2_get_theme_social() )
		add_option( 'tw2_theme_social', tw2_get_default_theme_social() );
	if ( false === tw2_get_theme_seo() )
		add_option( 'tw2_theme_seo', tw2_get_default_theme_seo() );
	if ( false === tw2_get_theme_ads() )
		add_option( 'tw2_theme_ads', tw2_get_default_theme_ads() );			
	register_setting(
		'tw2_general',       // Grupo de opciones, ver la llamada a settings_fields() en theme_general_render_page()
		'tw2_theme_general', // Opcion de Base de Datos, ver tw2_get_theme_general()
		'tw2_theme_general_validate' // La llamada de depuración, ver tw2_theme_general_validate()
	);
	register_setting(
		'tw2_social',       // Grupo de opciones, ver la llamada a settings_fields() en theme_social_render_page()
		'tw2_theme_social', // Opcion de Base de Datos, ver tw2_get_theme_social()
		'tw2_theme_social_validate' // La llamada de depuración, ver tw2_theme_social_validate()
	);
	register_setting(
		'tw2_seo',       // Grupo de opciones, ver la llamada a settings_fields() en theme_seo_render_page()
		'tw2_theme_seo', // Opcion de Base de Datos, ver tw2_get_theme_seo()
		'tw2_theme_seo_validate' // La llamada de depuración, ver tw2_theme_seo_validate()
	);
	register_setting(
		'tw2_ads',       // Grupo de opciones, ver la llamada a settings_fields() en theme_ads_render_page()
		'tw2_theme_ads', // Opcion de Base de Datos, ver tw2_get_theme_ads()
		'tw2_theme_ads_validate' // La llamada de depuración, ver tw2_theme_ads_validate()
	);
}
add_action( 'admin_init', 'tw2_theme_options_init' );

/* Cambia la capacidad requerida a 'edit_theme_options' para poder guardar las opciones del tema */

function tw2_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_tw2_options', 'tw2_option_page_capability' );

/* Añade la página de configuración del theme al menú de Administración, incluyendo alguna documentación de ayuda. */

add_action( 'admin_menu', 'tw2_theme_options_add_page' );
function tw2_theme_options_add_page() {
	//global $theme_page;
	$theme_page = add_theme_page(
		sprintf(__( 'Opciones de %s', 'tw2' ),'Trazos Web 2'),   // Nombre de la página
		sprintf(__( 'Opciones de %s', 'tw2' ),'Trazos Web 2'),   // Etiqueta en el menú
		'edit_theme_options',                // Capacidad requerida
		'tw2_theme_options',                 // Menu slug, usado para identificar únicamente a la página
		'tw2_theme_options_render_page' // Función que muestra la página de opciones
	);

	if ( $theme_page ){
		add_action( 'load-' . $theme_page, 'tw2_add_help_tabs_to_theme_page' );
		add_action( 'admin_print_styles-' . $theme_page, 'tw2_admin_enqueue_scripts' );	
	}
}

/*Añadir estilos y script necesarios para la administración del theme*/

function tw2_admin_enqueue_scripts() {
	wp_enqueue_style( 'tw2-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '1.0' );
	//wp_enqueue_script( 'tw2-theme-options', get_template_directory_uri() . '/inc/theme-options.js', false, '1.0' );
}

/* Añade la ayuda contextual para el tema */

function tw2_add_help_tabs_to_theme_page() {
	$help = '<p>' . __( 'Algunos temas dan opciones de personalización que son agrupadas en una sección de Opciones de Configuración del tema. Si cambias de tema, las opciones pueden desaparecer, si son especificas de ese tema. Tu actual tema, Trazos Web 2, te da las siguientes opciones:', 'tw2' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Generales</strong>: Aquí puedes seleccionar el estilo del tema, personalizar el logo del tema, así como el favicon, puedes activar los breadcrumbs "Migas de Pan", configurar los artículos relacionados, añadir tu cuenta de google analytics o usar otro servicio de estadísticas y modificar el texto "Copyright" del pie de página.', 'tw2' ) . '</li>' .
				'<li>' . __( '<strong>Redes Sociales</strong>: Aquí puedes escoger que servicio de RSS utilizar, que mensaje mostrar al final de los artículos en el RSS, ingresar tu dirección de suscripción por email, ingresar los datos de tu página en facebook, ingresar una imagen por defecto para Open Graph de Facebook e ingresar tus datos de tu perfil en Twitter.', 'tw2' ) . '</li>' .
				'<li>' . __( '<strong>SEO (Posicionamiento Web)</strong>: Aquí puedes configurar los datos de las etiquetas "description" y "keywords" de la página principal y decidir si quieres mostrar las opciones de SEO para poder configurar en cada uno de tus artículos.', 'tw2' ) . '</li>' .
				'<li>' . __( '<strong>Anuncios</strong>: Aquí decides si quieres mostrar los anuncios, que cantidad de anuncios deseas, si quieres que los anuncios roten o se mantengan fijos y puedes añadir todos los datos (URL, Imagen, Etiqueta ALT) de cada anuncio.', 'tw2' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Recuerda hacer clic en "Guardar Opciones" para guardar cualquier cambio que hayas hecho en las opciones del theme.', 'tw2' ) . '</p>';
			$sidebar = '<p><strong>' . __( 'Para más información:', 'tw2' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Foros de Ayuda</a>', 'tw2' ) . '</p>';

	$screen = get_current_screen();
	
	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array( 
			'id'	=> 'tw2_help',
			'title'	=> __('Ayuda del Tema','tw2'),
			'content' => $help,
			)
		);
		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2
		add_contextual_help( $screen, $help . $sidebar );
	}
}

/* Retorna las opciones por defecto del tema */

function tw2_get_default_theme_general() {
	$default_theme_options = array(
		'tw2_theme_style' => 'style1',
		'tw2_favicon' => '',
		'tw2_logo' => '',
		'tw2_breadcrumbs' => 0,
		'tw2_related_posts' => 5,
		'tw2_footer_copyright' => '&copy; '.gmdate(__('Y')).' <a href="'.home_url( '/' ).'" title="'.get_bloginfo('description').'">'.get_bloginfo('name').'</a>',
		'tw2_google_analytics' => '',
		'tw2_analytics' => '',
	);
	return apply_filters( 'tw2_default_theme_general', $default_theme_options );
}
function tw2_get_default_theme_social() {
	$default_theme_options = array(
		'tw2_feedburner' => home_url('/feed/'),
		'tw2_redirect_rss' => 0,
		'tw2_postrss' => "&copy; [[actualyear]] - [[bloglink]].<br> Artículo Original: [[permalink]].",
		'tw2_email_subscription' => '',
		'tw2_facebook_page' => '',
		'tw2_default_image' => '',
		'tw2_twitter_account' => '',
		'tw2_gplus_page' => '',
	);
	return apply_filters( 'tw2_default_theme_social', $default_theme_options );
}
function tw2_get_default_theme_seo() {
	$default_theme_options = array(
		'tw2_home_description' => '',
		'tw2_home_keywords' => '',
		'tw2_seo_meta' => 0,
	);
	return apply_filters( 'tw2_default_theme_seo', $default_theme_options );
}
function tw2_get_default_theme_ads() {
	$default_theme_options = array(
		'tw2_ads125' => 0,
		'tw2_ads_number' => 2,
		'tw2_ads_rotate' => 0,
		'tw2_ad_alt_1' => '',
		'tw2_ad_image_1' => '',
		'tw2_ad_url_1' => '',
		'tw2_ad_alt_2' => '',
		'tw2_ad_image_2' => '',
		'tw2_ad_url_2' => '',
		'tw2_ad_alt_3' => '',
		'tw2_ad_image_3' => '',
		'tw2_ad_url_3' => '',
		'tw2_ad_alt_4' => '',
		'tw2_ad_image_4' => '',
		'tw2_ad_url_4' => '',
		'tw2_ad_alt_5' => '',
		'tw2_ad_image_5' => '',
		'tw2_ad_url_5' => '',
		'tw2_ad_alt_6' => '',
		'tw2_ad_image_6' => '',
		'tw2_ad_url_6' => '',
		'tw2_ad_alt_7' => '',
		'tw2_ad_image_7' => '',
		'tw2_ad_url_7' => '',
		'tw2_ad_alt_8' => '',
		'tw2_ad_image_8' => '',
		'tw2_ad_url_8' => '',
	);
	return apply_filters( 'tw2_default_theme_ads', $default_theme_options );
}

/* Retona un array de opciones para el tema */

function tw2_get_theme_general() {
	return get_option( 'tw2_theme_general', tw2_get_default_theme_general() );
}
function tw2_get_theme_social() {
	return get_option( 'tw2_theme_social', tw2_get_default_theme_social() );
}
function tw2_get_theme_seo() {
	return get_option( 'tw2_theme_seo', tw2_get_default_theme_seo() );
}
function tw2_get_theme_ads() {
	return get_option( 'tw2_theme_ads', tw2_get_default_theme_ads() );
}

/* Retorna la página de configuración del Tema, con pestañas. */

function tw2_theme_options_render_page() {
	global $pagenow;

	if ( $pagenow == 'themes.php' && $_GET['page'] == 'tw2_theme_options' ) : 
	    if ( isset ( $_GET['tab'] ) ) : 
	        $tab = $_GET['tab']; 
	    else: 
	        $tab = 'general'; 
	    endif; 
	    echo '<div class="wrap">';
	    echo '<h2>',sprintf( __( 'Opciones de %s', 'tw2' ), 'Trazos Web 2' ),'</h2>';
	    tw2_admin_tabs($tab);
	    switch ( $tab ) : 
	        case 'general' : 
	            theme_general_render_page(); 
	            break; 
	        case 'social' : 
	            theme_social_render_page(); 
	            break; 
	        case 'seo' : 
	            theme_seo_render_page(); 
	            break;
	        case 'ads' : 
	            theme_ads_render_page(); 
	            break;     
	    endswitch;
	    echo '<p>'.sprintf( __( '%1$s diseñado por %2$s', 'tw2' ), '<strong>Trazos Web 2</strong>', '<a title="Trazos Web - Blogging, Diseño y Desarrollo Web" href="http://www.trazos-web.com/">Trazos Web</a>' ).' | <a href="http://twitter.com/trazosweb">'.__('Sígueme en Twitter!.','tw2').'</a> | <a href="http://www.facebook.com/TrazosWeb">'.sprintf(__('Únete a %s en Facebook!','tw2'), 'Trazos Web').'</a> | <a href="http://plus.google.com/b/105728563056280204005/105728563056280204005/posts">'.sprintf(__('Únete a %s en Google Plus!,','tw2'),'Trazos Web').'</a></p>';
	    echo '</div>';
	endif;
}

/* Retorna los diferentes estilos de color del tema */

function tw2_color_styles() {
	$color_style_options = array(
		'style1' => array(
			'value' => 'style1',
			'label' => __( 'Azul', 'tw2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/style1.png',
		),
		'style2' => array(
			'value' => 'style2',
			'label' => __( 'Rojo', 'tw2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/style2.png',
		),
		'style3' => array(
			'value' => 'style3',
			'label' => __( 'Verde', 'tw2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/style3.png',
		),
		'style4' => array(
			'value' => 'style4',
			'label' => __( 'Negro', 'tw2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/style4.png',
		),
		'style5' => array(
			'value' => 'style5',
			'label' => __( 'Morado', 'tw2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/style5.png',
		),
	);
	return apply_filters( 'tw2_color_styles', $color_style_options );
}

/* Retorna las diferentes pestañas de la configuración del Tema */

function tw2_admin_tabs( $current = 'general' ) {
    $tabs = array( 'general' => __('Generales','tw2'), 'social' => __('Redes Sociales','tw2'), 'seo' => __('SEO','tw2'), 'ads' => __('Anuncios','tw2') );
    $links = array(); 
    foreach( $tabs as $tab => $name ) : 
        if ( $tab == $current ) : 
            $links[] = "<a class='nav-tab nav-tab-active' href='?page=tw2_theme_options&tab=$tab'>$name</a>"; 
        else : 
            $links[] = "<a class='nav-tab' href='?page=tw2_theme_options&tab=$tab'>$name</a>"; 
        endif; 
    endforeach;
    screen_icon(); 
    echo '<h2 class="nav-tab-wrapper">'; 
    foreach ( $links as $link ) 
        echo $link; 
    echo '</h2>';
}

/* Imprime el formulario para la pestaña de Opciones Generales */

function theme_general_render_page() {
	settings_errors();
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Opciones correctamente guardadas','tw2' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'tw2_general' );
		$options = tw2_get_theme_general();
		?>
		<table class="form-table">
			<tr valign="top" class="image-radio-option color-style"><th scope="row"><?php _e( 'Estilos de color', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Estilos de color', 'tw2' ); ?></span></legend>
					<?php
						foreach ( tw2_color_styles() as $style ) {
							?>
							<div class="layout">
							<label class="description">
								<input type="radio" name="tw2_theme_general[tw2_theme_style]" value="<?php echo esc_attr( $style['value'] ); ?>" <?php checked( $options['tw2_theme_style'], $style['value'] ); ?>>
								<span class="image">
									<img src="<?php echo esc_url( $style['thumbnail'] ); ?>" width="100" height="100" alt="<?php echo esc_attr( $style['label'] ); ?>">
									<?php echo $style['label']; ?>
								</span>
							</label>
							</div>
					<?php } ?>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Logo', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Logo', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_general[tw2_logo]" id="tw2_logo" value="<?php echo esc_attr( $options['tw2_logo'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la dirección URL de la imagen del Logo o deja en blanco para utilizar solo texto.<br>Ej:','tw2'); echo ' <code>'.get_template_directory_uri().'/img/logo.png</code><br>'; _e('La imagen debe tener un tamaño de 384x72 pixéles.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Favicon', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Favicon', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_general[tw2_favicon]" id="tw2_favicon" value="<?php echo esc_attr( $options['tw2_favicon'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la dirección URL de la imagen del favicon o deja en blanco para no utilizarlo.<br>Ej:','tw2'); echo '<code>'.get_template_directory_uri().'/favicon.ico</code><br>'; _e('La imagen debe tener un tamaño de 32x32 pixéles.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Habilitar Breadcrumbs', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Habilitar Breadcrumbs', 'tw2' ); ?></span></legend>
						<input type="checkbox" name="tw2_theme_general[tw2_breadcrumbs]" id="tw2_breadcrumbs" value="1" <?php checked( $options['tw2_breadcrumbs'], 1 ); ?>>
						<br>
						<span class="description"><?php _e( 'Habilita los Breadcrumbs "Migas de Pan" que aparecen al principio de las páginas, artículos, categorías, etiquetas y resultados de búsqueda del blog.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'No. de Artículos Relacionados', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'No. de Artículos Relacionados', 'tw2' ); ?></span></legend>
						<input class="shortdata" type="text" name="tw2_theme_general[tw2_related_posts]" id="tw2_related_posts" value="<?php echo esc_attr( $options['tw2_related_posts'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa aquí el número de artículos relacionados que quieres que aparezcan en tus artículos. Si dejas este campo vacío, por defecto aparecerán 5 artículos relacionados.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Texto de Copyright', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Texto de Copyright', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_general[tw2_footer_copyright]" id="tw2_footer_copyright" value="<?php echo esc_attr( $options['tw2_footer_copyright'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa el texto de "Copyright" que quieres que aparezca en el pié de página del tema. Puedes usar HTML.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'ID de Google Analytics', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'ID de Google Analytics', 'tw2' ); ?></span></legend>
						<input class="shortdata" type="text" name="tw2_theme_general[tw2_google_analytics]" id="tw2_google_analytics" value="<?php echo esc_attr( $options['tw2_google_analytics'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa aquí el "ID de propiedad web" de tu blog para utilizar las estadísticas de Google Analytics, o deja en blanco para no utilizarlo. Debe ser algo como <code>UA-XXXXXXX-X</code>.<br>Más información en: <a href="http://support.google.com/googleanalytics/bin/answer.py?answer=113500" title="ID de propiedad web" target="_blank">ID de propiedad web</a>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Scripts en el Pié de Página', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Scripts en el Pié de Página', 'tw2' ); ?></span></legend>
						<textarea class="longdata" id="tw2_analytics" name="tw2_theme_general[tw2_analytics]" rows="" cols=""><?php echo stripslashes($options['tw2_analytics']); ?></textarea>
						<br>
						<span class="description"><?php _e( 'Ingresa aquí el código de los scripts de javascript que desees poner justo antes de la etiqueta <code>&lt;/body&gt;</code> del tema.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php submit_button( __('Guardar Opciones','tw2') ) ?>
	</form>
<?php
}

/* Imprime el formulario para la pestaña de Redes Sociales */

function theme_social_render_page() {
	settings_errors();
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Opciones correctamente guardadas','tw2' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'tw2_social' );
		$options = tw2_get_theme_social();
		?>
		<table class="form-table">
			<tr valign="top"><th scope="row"><?php _e( 'URL de Feedburner', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'URL de Feedburner', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_social[tw2_feedburner]" id="tw2_feedburner" value="<?php echo esc_attr( $options['tw2_feedburner'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Si tienes una cuenta de <a href="http://www.feedburner.com/" target="_blank">Feedburner</a> para gestionar las estad&iacute;sticas de tu Feed, ingresa aquí la URL completa.<br>Ejemplo: <code>http://feedproxy.google.com/TuBlog</code> ó <code>http://feeds.feedburner.com/TuBlog</code>.<br>Por defecto se utiliza la dirección de RSS del blog.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Redireccionar RSS', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Redireccionar RSS', 'tw2' ); ?></span></legend>
						<input type="checkbox" name="tw2_theme_social[tw2_redirect_rss]" id="tw2_redirect_rss" value="1" <?php checked( $options['tw2_redirect_rss'], 1 ); ?>>
						<br>
						<span class="description"><?php _e( 'Marca esta casilla si deseas redireccionar todos los feeds de tu blog a la dirección que pusiste en el campo anterior: "URL de Feedburner".', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Texto de Pié de RSS', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Texto de Pié de RSS', 'tw2' ); ?></span></legend>
						<textarea class="longdata" id="tw2_postrss" name="tw2_theme_social[tw2_postrss]" rows="" cols=""><?php echo stripslashes($options['tw2_postrss']); ?></textarea>
						<br>
						<span class="description"><?php _e( 'Ingresa aquí el texto que quieres que aparezca al fondo de cada uno de tus artículos en el Feed RSS del blog.<br>Puedes utilizar las siguiente etiquetas:<br><code><b>[[posturl]]</b></code>: URL del artículo. - <code><b>[[permalink]]</b></code>: Enlace del artículo.<br><code><b>[[posttitle]]</b></code>: Título del artículo. - <code><b>[[bloglink]]</b></code>: Enlace del blog.<br><code><b>[[blogurl]]</b></code>: URL del blog - <code><b>[[actualyear]]</b></code>: Año actual.<br><code><b>[[blogname]]</b></code>: Nombre del Blog. - <code><b>[[blogdescription]]</b></code>: Descripción del blog.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Suscripción por Email', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Suscripción por Email', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_social[tw2_email_subscription]" id="tw2_email_subscription" value="<?php echo esc_attr( $options['tw2_email_subscription'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la dirección de suscripción por email de tu blog.<br>Ej: <code>http://feedburner.google.com/fb/a/mailverify?uri=TuBlog&loc=es_ES</code>.<br>Más información en: <a href="http://support.google.com/feedburner/bin/answer.py?answer=78982" title="FeedBurner Email Overview and FAQ" target="_blank">FeedBurner Email Overview and FAQ</a>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Página en Facebook', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Página en Facebook', 'tw2' ); ?></span></legend>
						<input class="shortdata" type="text" name="tw2_theme_social[tw2_facebook_page]" id="tw2_facebook_page" value="<?php echo esc_attr( $options['tw2_facebook_page'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa el "username" de la página en facebook de tu blog.<br>Ej: <code>TrazosWeb</code>.<br>Más información en: <a href="http://www.facebook.com/help/pages/usernames" title="Nombres de usuario para las páginas de Facebook" target="_blank">Nombres de usuario para las páginas de Facebook</a> y <a href="http://www.facebook.com/username/" title="Nombre de Usuario" target="_blank">Nombre de Usuario</a>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Imagen por defecto de Open Graph', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Imagen por defecto de Open Graph', 'tw2' ); ?></span></legend>
						<input type="text" name="tw2_theme_social[tw2_default_image]" id="tw2_default_image" value="<?php echo esc_attr( $options['tw2_default_image'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la dirección URL de la imagen por defecto de Open Graph de Facebook o deja en blanco para utilizar por defecto la imagen del tema.<br>Ej:','tw2'); echo ' <code>'.get_template_directory_uri().'/img/fb_og.png</code><br>'; _e('La imagen puede tener cualquier tamaño.<br> Más información de Open Graph: <a href="https://developers.facebook.com/docs/opengraph/" title="Open Graph Protocol" target="_blank">Open Graph Protocol</a>', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Perfil en Twitter', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Perfil en Twitter', 'tw2' ); ?></span></legend>
						<input class="shortdata" type="text" name="tw2_theme_social[tw2_twitter_account]" id="tw2_twitter_account" value="<?php echo esc_attr( $options['tw2_twitter_account'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa el "username" del perfil en Twitter de tu blog.<br>Ej: <code>TrazosWeb</code>.<br>Más información en: <a href="http://support.twitter.com/articles/14609-how-to-change-your-username" title="Cómo cambiar tu nombre de usuario" target="_blank">Cómo cambiar tu nombre de usuario</a>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Página en Google+', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Página en Google+', 'tw2' ); ?></span></legend>
						<input class="shortdata" type="text" name="tw2_theme_social[tw2_gplus_page]" id="tw2_gplus_page" value="<?php echo esc_attr( $options['tw2_gplus_page'] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la dirección URL de la página de tu blog en Google+.<br>Debe ser algo como por Ej.: <code>https://plus.google.com/b/105728563056280204005/</code>.<br>Más información en: <a href="http://support.google.com/plus/bin/answer.py?answer=1710600&topic=1710599&ctx=topic" title="Cómo cambiar tu nombre de usuario" target="_blank">Acerca de Páginas de Google+</a>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php submit_button( __('Guardar Opciones','tw2') ) ?>
	</form>
<?php
}
/* Imprime el formulario para la pestaña de SEO */

function theme_seo_render_page() {
	settings_errors();
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Opciones correctamente guardadas','tw2' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'tw2_seo' );
		$options = tw2_get_theme_seo();
		?>
		<table class="form-table">
			<tr valign="top"><th scope="row"><?php _e( 'Meta Description de la Página de Inicio', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Meta Description de la Página de Inicio', 'tw2' ); ?></span></legend>
						<textarea id="tw2_home_description" name="tw2_theme_seo[tw2_home_description]" rows="" cols=""><?php echo stripslashes($options['tw2_home_description']); ?></textarea>
						<br>
						<span class="description"><?php _e( 'Ingresa una pequeña descripción de tu blog para los buscadores. Ésta no debe exceder los 160 caracteres.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'Meta Keywords de la Página de Inicio', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'Meta Keywords de la Página de Inicio', 'tw2' ); ?></span></legend>
						<textarea id="tw2_home_keywords" name="tw2_theme_seo[tw2_home_keywords]" rows="" cols=""><?php echo stripslashes($options['tw2_home_keywords']); ?></textarea>
						<br>
						<span class="description"><?php _e( 'Ingresa las palabras clave más importantes de tu sitio. Separa estas palabras con comas.<br>Ejemplo: <code>palabra clave 1, palabra clave 2, palabra clave 3</code>.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( 'SEO en Artículos y Páginas', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( 'SEO en Artículos y Páginas', 'tw2' ); ?></span></legend>
						<input type="checkbox" name="tw2_theme_seo[tw2_seo_meta]" id="tw2_seo_meta" value="1" <?php checked( $options['tw2_seo_meta'], 1 ); ?>>
						<br>
						<span class="description"><?php _e( 'Si marcas esta casilla, aparecerá un área para escribir los meta keywords y meta description para cada página o artículo que estés escribiendo.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php submit_button( __('Guardar Opciones','tw2') ) ?>
	</form>
<?php
}
/* Imprime el formulario para la pestaña de Anuncios */

function theme_ads_render_page() {
	settings_errors();
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Opciones correctamente guardadas','tw2' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'tw2_ads' );
		$options = tw2_get_theme_ads();
		?>
		<table class="form-table">
			<tr valign="top"><th scope="row"><?php _e( '¿Quieres mostrar los anuncios?', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( '¿Quieres mostrar los anuncios?', 'tw2' ); ?></span></legend>
						<input type="checkbox" name="tw2_theme_ads[tw2_ads125]" id="tw2_ads125" value="1" <?php checked( $options['tw2_ads125'], 1 ); ?>>
						<br>
						<span class="description"><?php _e( 'Marca esta casilla si quieres que se muestren los anuncios en la barra lateral.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( '¿Cuantos anuncios quieres mostrar?', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( '¿Cuantos anuncios quieres mostrar?', 'tw2' ); ?></span></legend>
						<select id="tw2_ads_number" name="tw2_theme_ads[tw2_ads_number]">
						<?php
						for ( $i=1; $i<9;$i++ ) :
							echo '<option value="' . $i . '" ' . selected( $options['tw2_ads_number'], $i, false) . '>' . $i . '</option>';
						endfor;
						?>
						</select>
						<br>
						<span class="description"><?php _e( 'Indica el número de anuncios que deseas mostrar (1-8).', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e( '¿Quieres que los anuncios roten?', 'tw2' ); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php _e( '¿Quieres que los anuncios roten?', 'tw2' ); ?></span></legend>
						<input type="checkbox" name="tw2_theme_ads[tw2_ads_rotate]" id="tw2_ads_rotate" value="1" <?php checked( $options['tw2_ads_rotate'], 1 ); ?>>
						<br>
						<span class="description"><?php _e( 'Marca esta casilla para que el orden de los anuncios sea aleatorio.', 'tw2' ); ?></span>
					</fieldset>
				</td>
			</tr>
			<?php
			for ( $i=1; $i<9;$i++ ) :
			?>	
			<tr valign="top"><th scope="row"><?php printf(__( 'Datos del Anuncio #%s', 'tw2' ), $i); ?></th>
				<td>
					<fieldset><legend class="screen-reader-text"><span><?php printf(__( 'Datos del Anuncio #%s', 'tw2' ), $i); ?></span></legend>
						<label for="tw2_theme_ads[tw2_ad_image_<?php echo $i;?>]"><?php _e( 'Imagen del Anuncio:', 'tw2' ); ?></label><br><input type="text" name="tw2_theme_ads[tw2_ad_image_<?php echo $i;?>]" id="tw2_ad_image_<?php echo $i;?>" value="<?php echo esc_attr( $options['tw2_ad_image_'.$i] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la URL de la imagen de este anuncio. Ej:','tw2'); echo ' <code>'.get_template_directory_uri().'/img/ad_'.$i.'.png</code>'; ?></span>
						<br>
						<label for="tw2_theme_ads[tw2_ad_alt_<?php echo $i;?>]"><?php _e( 'Etiqueta ALT de la Imagen:', 'tw2' ); ?></label><br><input type="text" name="tw2_theme_ads[tw2_ad_alt_<?php echo $i;?>]" id="tw2_ad_alt_<?php echo $i;?>" value="<?php echo esc_attr( $options['tw2_ad_alt_'.$i] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa una pequeñísima descripción del anuncio o de la imagen.','tw2'); ?></span>
						<br>
						<label for="tw2_theme_ads[tw2_ad_url_<?php echo $i;?>]"><?php _e( 'URL del Anuncio:', 'tw2' ); ?></label><br><input type="text" name="tw2_theme_ads[tw2_ad_url_<?php echo $i;?>]" id="tw2_ad_url_<?php echo $i;?>" value="<?php echo esc_attr( $options['tw2_ad_url_'.$i] ); ?>">
						<br>
						<span class="description"><?php _e( 'Ingresa la URL a donde quieres que este anuncio se dirija. Ej:','tw2'); echo ' <code>'.home_url( '/' ).'</code>'; ?></span>
					</fieldset>
				</td>
			</tr>
			<?php
			endfor;
			?>
		</table>
		<?php submit_button( __('Guardar Opciones','tw2') ) ?>
	</form>
<?php
}

/*Validar datos de Opciones Generales*/

function tw2_theme_general_validate( $input ) {
	$output = $defaults = tw2_get_default_theme_general();
	if ( isset( $input['tw2_theme_style'] ) && array_key_exists( $input['tw2_theme_style'], tw2_color_styles() ) )
		$output['tw2_theme_style'] = $input['tw2_theme_style'];
	if ( isset( $input['tw2_favicon'] ) )
		$output['tw2_favicon'] = wp_filter_nohtml_kses( $input['tw2_favicon'] );
	if ( isset( $input['tw2_logo'] ) )
		$output['tw2_logo'] = wp_filter_nohtml_kses( $input['tw2_logo'] );
	if ( isset( $input['tw2_breadcrumbs'] ) )
		$output['tw2_breadcrumbs'] = $input['tw2_breadcrumbs'];
	if ( isset( $input['tw2_related_posts'] ) )
		$output['tw2_related_posts'] = $input['tw2_related_posts'];
	if ( isset( $input['tw2_footer_copyright'] ) )
		$output['tw2_footer_copyright'] = wp_filter_post_kses( $input['tw2_footer_copyright'] );
	if ( isset( $input['tw2_google_analytics'] ) )
		$output['tw2_google_analytics'] = wp_filter_nohtml_kses($input['tw2_google_analytics']);
	if ( isset( $input['tw2_analytics'] ) )
		$output['tw2_analytics'] = stripslashes($input['tw2_analytics']);				

	return apply_filters( 'tw2_theme_general_validate', $output, $input, $defaults );
}

/*Validar datos de Opciones de Redes Sociales*/

function tw2_theme_social_validate( $input ) {
	$output = $defaults = tw2_get_default_theme_social();
	if ( isset( $input['tw2_feedburner'] ) )
		$output['tw2_feedburner'] = wp_filter_nohtml_kses($input['tw2_feedburner']);
	if ( isset( $input['tw2_redirect_rss'] ) )
		$output['tw2_redirect_rss'] = $input['tw2_redirect_rss'];
	if ( isset( $input['tw2_postrss'] ) )
		$output['tw2_postrss'] = wp_filter_post_kses($input['tw2_postrss']);
	if ( isset( $input['tw2_email_subscription'] ) )
		$output['tw2_email_subscription'] = wp_filter_nohtml_kses($input['tw2_email_subscription']);
	if ( isset( $input['tw2_facebook_page'] ) )
		$output['tw2_facebook_page'] = wp_filter_nohtml_kses($input['tw2_facebook_page']);
	if ( isset( $input['tw2_default_image'] ) )
		$output['tw2_default_image'] = wp_filter_nohtml_kses($input['tw2_default_image']);
	if ( isset( $input['tw2_twitter_account'] ) )
		$output['tw2_twitter_account'] = wp_filter_nohtml_kses($input['tw2_twitter_account']);	
	if ( isset( $input['tw2_gplus_page'] ) )
		$output['tw2_gplus_page'] = wp_filter_nohtml_kses($input['tw2_gplus_page']);					
	return apply_filters( 'tw2_theme_social_validate', $output, $input, $defaults );
}

/*Validar datos de Opciones de SEO */

function tw2_theme_seo_validate( $input ) {
	$output = $defaults = tw2_get_default_theme_seo();
	if ( isset( $input['tw2_home_description'] ) )
		$output['tw2_home_description'] = wp_filter_nohtml_kses($input['tw2_home_description']);
	if ( isset( $input['tw2_home_keywords'] ) )
		$output['tw2_home_keywords'] = wp_filter_nohtml_kses($input['tw2_home_keywords']);
	if ( isset( $input['tw2_seo_meta'] ) )
		$output['tw2_seo_meta'] = $input['tw2_seo_meta'];
	return apply_filters( 'tw2_theme_seo_validate', $output, $input, $defaults );
}

/*Validar datos de Opciones  de Anuncios*/

function tw2_theme_ads_validate( $input ) {
	$output = $defaults = tw2_get_default_theme_ads();
	if ( isset( $input['tw2_ads125'] ) )
		$output['tw2_ads125'] = $input['tw2_ads125'];
	if ( isset( $input['tw2_ads_number'] ) )
		$output['tw2_ads_number'] = (int) $input['tw2_ads_number'];
	if ( isset( $input['tw2_ads_rotate'] ) )
		$output['tw2_ads_rotate'] = $input['tw2_ads_rotate'];
	for ( $i=1; $i<7;$i++ ) :
		if ( isset( $input['tw2_ad_alt_'.$i] ) )
			$output['tw2_ad_alt_'.$i] = esc_attr($input['tw2_ad_alt_'.$i]);
		if ( isset( $input['tw2_ad_image_'.$i] ) )
			$output['tw2_ad_image_'.$i] = esc_url_raw( $input['tw2_ad_image_'.$i] );
		if ( isset( $input['tw2_ad_url_'.$i] ) )
			$output['tw2_ad_url_'.$i] = esc_url_raw( $input['tw2_ad_url_'.$i] );
	endfor;
	return apply_filters( 'tw2_theme_ads_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 */
function tw2_enqueue_theme_style() {
	$options = tw2_get_default_theme_general();
	$theme_style = $options['tw2_theme_style'];

	wp_enqueue_style( $theme_style, get_template_directory_uri() . '/css/'.$theme_style.'.css', array(), null );
	do_action( 'tw2_enqueue_theme_style', $theme_style );
}
//add_action( 'wp_enqueue_scripts', 'tw2_enqueue_theme_style' );
/**
 * Add a style block to the theme for the current link color.
 */
function tw2_print_feed() {
	$options = tw2_get_theme_social();
	$feedburner = $options['tw2_feedburner'];
	if ( $feedburner == '' )
		$feedburner = get_bloginfo('rss2_url');
?>
	<link rel="alternate" title="RSS 2.0" href="<?php echo $feedburner; ?>">
<?php
}
//add_action( 'wp_head', 'tw2_print_feed' );
/**
 * Add a style block to the theme for the current link color.
 */
function tw2_print_favicon() {
	$options = tw2_get_theme_general();
	$favicon = $options['tw2_favicon'];
	if ( $favicon == '' )
		$favicon = get_bloginfo('stylesheet_directory') ."/img/favicon.ico";
?>
	<link rel="shortcut icon" href="<?php echo $favicon; ?>">
<?php
}
//add_action( 'wp_head', 'tw2_print_favicon' );
?>