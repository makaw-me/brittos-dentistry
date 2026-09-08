<?php
/**
 * Site header: logo, primary nav, mobile toggle, appointment CTA.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_url    = get_template_directory_uri() . '/assets/images/logo.webp';
$clinic_name = brittos_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );
$phone       = brittos_clinic_field( 'phone' );
?>
<header class="site-header" role="banner">
	<div class="container site-header__inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-header__brand" aria-label="<?php echo esc_attr( sprintf( /* translators: %s clinic name */ __( '%s — Home', 'brittos-dentistry' ), $clinic_name ) ); ?>">
			<img
				src="<?php echo esc_url( $logo_url ); ?>"
				alt=""
				class="site-header__logo"
				width="160"
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

				<?php if ( $phone ) : ?>
					<a class="button button--ghost site-header__phone" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
						<svg class="site-header__phone-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
						<span><?php echo esc_html( $phone ); ?></span>
					</a>
				<?php endif; ?>

				<a class="button button--primary site-header__cta" href="#appointment-form">
					<span class="button__text"><?php esc_html_e( 'Book appointment', 'brittos-dentistry' ); ?></span>
					<span class="button__icon" aria-hidden="true">
						<svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</span>
				</a>
			</div>
		</nav>

	</div>
</header>
