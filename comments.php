<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__('Por favor no cargues esta página directamente. Gracias!','tw2'));
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e( 'Este artículo está protegido con contraseña. Ingresa la contraseña para ver los comentarios.', 'tw2' ); ?></p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<div id="comments_wrap">
<?php if ( have_comments() ) : ?>
	<h2>	<?php printf( _n( 'Hay un Comentario en "%2$s"', 'Hay %1$s Comentarios en "%2$s"', get_comments_number(), 'tw2' ), number_format_i18n( get_comments_number() ), get_the_title()); ?></h2>
	<ol class="commentlist">
	<?php wp_list_comments('avatar_size=90&callback=custom_comment&type=comment'); ?>
	</ol>    
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(__( '&larr; Comentarios Antiguos', 'tw2' )) ?></div>
		<div class="alignright"><?php next_comments_link(__( 'Nuevos Comentarios &rarr;', 'tw2' )) ?></div>
		<div class="fix"></div>
	</div>
	<br />
	<?php if ( $comments_by_type['pings'] ) : ?>
    <h2 id="pings"><?php _e( 'Trackbacks/Pingbacks', 'tw2' ); ?></h2>
    <ol class="pinglist">
    <?php wp_list_comments('callback=custom_comment&type=pings'); ?>
    </ol>
    <?php endif; ?>
<?php else : ?>
	<?php if ('open' == $post->comment_status) : ?>
		 
	 <?php else : ?>
		
		<p class="nocomments"><?php _e( 'Los comentarios están cerrados.', 'tw2' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
</div> <!-- end #comments_wrap -->
<?php if ('open' == $post->comment_status) : ?>
<div id="respond">
<h2><?php comment_form_title( __('Deja un Comentario','tw2'), __('Déjale un Comentario a %s','tw2') ); ?></h2>
<div class="cancel-comment-reply">
	<p><small><?php cancel_comment_reply_link(); ?></small></p>
</div>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
<p class="must-log-in"><?php  sprintf( __( 'Debes <a href="%s">ingresar</a> para publicar un comentario.', 'tw2' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ); ?></p>
<?php else : ?>
<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
<p class="logged-in-as"><?php sprintf( __( 'Has ingresado como <a href="%1$s">%2$s</a>. <a href="%3$s" title="Salir de esta cuenta">Salir &raquo;</a>', 'tw2' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ); ?></p>
<?php else : ?>
<p><input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="42" tabindex="1" /><label for="author"><strong><?php _e( 'Nombre', 'tw2' ); ?></strong> <?php if ($req) _e( '(requerido)', 'tw2' ); ?></label></p>
<p><input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="42" tabindex="2" /><label for="email"><?php _e( '<strong>Mail</strong> (no será publicado)', 'tw2' ); ?> <?php if ($req) _e( '(requerido)', 'tw2' ); ?></label></p>
<p><input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="42" tabindex="3" /><label for="url"><strong><?php _e( 'Sitio Web', 'tw2' ); ?></strong></label></p>
<?php endif; ?>
<!--<p class="form-allowed-tags"><?php sprintf( __( 'Puedes usar las siguientes etiquetas y atributos de <abbr title="HyperText Markup Language">HTML</abbr>: %s' ), ' <code>' . allowed_tags() . '</code>' ); ?></p>-->
<p><textarea name="comment" id="comment" style="width:97%;" rows="10" tabindex="4"></textarea></p>
<p><input name="submit" type="submit" id="enviarc" tabindex="5" value="<?php _e( 'Envía tu Comentario', 'tw2' ); ?>" />
<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
</p>
<?php comment_id_fields(); ?>
<?php do_action('comment_form', $post->ID); ?>
</form>
<?php endif; // If logged in ?>
<div class="fix"></div>
</div> <!-- end #respond -->
<?php endif; // if you delete this the sky will fall on your head ?>