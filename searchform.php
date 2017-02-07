<?php
/**
 * Template for displaying search forms in Conan06 Material Design
 *
 * @package WordPress
 * @subpackage ConanMD
 * @since 1.0
 * @version 1.0
 */
?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="mdl-textfield mdl-js-textfield">
		<label class="mdl-textfield__label" for="<?php echo $unique_id; ?>"><span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'ConanMD' ); ?></span></label>
		<input class="mdl-textfield__input" type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'ConanMD' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
		<button type="submit" class="mdl-button mdl-js-button mdl-button--icon search-submit">
			<i class="material-icons">search</i>
			<span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'ConanMD' ); ?></span>
		</button>
	</div>
</form>
