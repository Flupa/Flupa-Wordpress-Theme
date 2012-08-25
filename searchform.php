<?php
/**
 * The template for displaying search forms in Flupa Wordpress Theme
 *
 * @package Flupa Wordpress Theme
 * @since Flupa Wordpress Theme 1.0
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'flupa_wordpress_theme' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php esc_attr_e( 'Search &hellip;', 'flupa_wordpress_theme' ); ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'flupa_wordpress_theme' ); ?>" />
	</form>
