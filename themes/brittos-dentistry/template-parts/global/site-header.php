<?php
/**
 * Site header: logo, primary nav, mobile toggle, appointment CTA.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_url    = get_template_directory_uri() . '/assets/images/logo.png';
$clinic_name = brittos_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );

// On the homepage the hero already carries the primary/secondary CTAs and
// its own contact details, so the header stays transparent and nav-only
// until the visitor scrolls, when it solidifies for legibility over
// ordinary content (see assets/js/navigation.js).
$header_classes = array( 'site-header' );
if ( is_front_page() ) {
	$header_classes[] = 'site-header--transparent';
}
?>
<header class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>" role="banner">
	<div class="container site-header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__brand" aria-label="<?php echo esc_attr( sprintf( /* translators: %s clinic name */ __( '%s — Home', 'brittos-dentistry' ), $clinic_name ) ); ?>">
			<img
				src="<?php echo esc_url( $logo_url ); ?>"
				alt=""
				class="site-header__logo site-header__logo--light"
				width="160"
				height="128"
				decoding="async"
			>
			<img
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo-dark.png' ); ?>"
				alt=""
				class="site-header__logo site-header__logo--dark"
				width="158"
				height="128"
				decoding="async"
			>
			<span class="site-header__name"><?php echo esc_html( $clinic_name ); ?></span>
		</a>

		<button
			type="button"
			class="nav-toggle"
			aria-expanded="false"
			aria-controls="primary-navigation"
			aria-label="<?php esc_attr_e( 'Open menu', 'brittos-dentistry' ); ?>"
		>
			<span class="nav-toggle__box" aria-hidden="true">
				<span class="nav-toggle__bar"></span>
				<span class="nav-toggle__bar"></span>
				<span class="nav-toggle__bar"></span>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'brittos-dentistry' ); ?></span>
		</button>

		<div class="nav-backdrop" data-nav-backdrop aria-hidden="true"></div>

		<nav id="primary-navigation" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'brittos-dentistry' ); ?>">
			<div class="primary-nav__header">
				<div class="primary-nav__brand-mini">
					<span class="primary-nav__badge-dot" aria-hidden="true"></span>
					<span class="primary-nav__label"><?php echo esc_html( $clinic_name ); ?></span>
				</div>
				<button type="button" class="nav-close" aria-label="<?php esc_attr_e( 'Close menu', 'brittos-dentistry' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<line x1="18" y1="6" x2="6" y2="18"></line>
						<line x1="6" y1="6" x2="18" y2="18"></line>
					</svg>
				</button>
			</div>
			
			<div class="primary-nav__body">
				<?php brittos_nav_menu( 'primary' ); ?>
			</div>

			<div class="primary-nav__actions">
				<button type="button" class="theme-toggle" data-theme-toggle aria-pressed="false" aria-label="<?php esc_attr_e( 'Switch to dark mode', 'brittos-dentistry' ); ?>" title="<?php esc_attr_e( 'Switch to dark mode', 'brittos-dentistry' ); ?>">
					<svg class="theme-toggle__icon theme-toggle__icon--moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
					</svg>
					<svg class="theme-toggle__icon theme-toggle__icon--sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="12" cy="12" r="5"></circle>
						<line x1="12" y1="1" x2="12" y2="3"></line>
						<line x1="12" y1="21" x2="12" y2="23"></line>
						<line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
						<line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
						<line x1="1" y1="12" x2="3" y2="12"></line>
						<line x1="21" y1="12" x2="23" y2="12"></line>
						<line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
						<line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
					</svg>
					<span class="theme-toggle__label"><?php esc_html_e( 'Color mode', 'brittos-dentistry' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Toggle color mode', 'brittos-dentistry' ); ?></span>
				</button>
			</div>
		</nav>

	</div>
</header>
