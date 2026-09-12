<?php
/**
 * Accessibility helpers. Skip link is printed in header.php; this file
 * covers the WordPress-API-driven parts (menu args, comment form labels).
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add a fallback, accessible menu when no menu has been assigned yet
 * in Appearance > Menus, so navigation never renders empty/broken.
 *
 * @return void
 */
function brittos_fallback_menu() {
	echo '<ul class="primary-nav__list">';
	echo '<li class="primary-nav__item"><a class="primary-nav__link" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'brittos-dentistry' ) . '</a></li>';

	$pages = get_pages( array( 'sort_column' => 'menu_order', 'number' => 5 ) );
	foreach ( $pages as $page ) {
		printf(
			'<li class="primary-nav__item"><a class="primary-nav__link" href="%1$s">%2$s</a></li>',
			esc_url( get_permalink( $page ) ),
			esc_html( get_the_title( $page ) )
		);
	}
	echo '</ul>';
}

/**
 * Ensure `wp_nav_menu()` always outputs a usable, keyboard-navigable list,
 * even without a container, and never throws notices when a menu location
 * has nothing assigned.
 *
 * @param string $location Menu location key.
 * @param array  $extra    Extra args merged onto the defaults.
 * @return void
 */
function brittos_nav_menu( $location, $extra = array() ) {
	$defaults = array(
		'theme_location' => $location,
		'container'      => false,
		'items_wrap'     => '<ul class="primary-nav__list">%3$s</ul>',
		'fallback_cb'    => 'brittos_fallback_menu',
		'depth'          => 2,
	);

	if ( 'primary' === $location && class_exists( 'Brittos_Primary_Nav_Walker' ) ) {
		$defaults['walker'] = new Brittos_Primary_Nav_Walker();
	}

	wp_nav_menu( wp_parse_args( $extra, $defaults ) );
}
