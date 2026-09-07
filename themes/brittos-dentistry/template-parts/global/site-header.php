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
				fetchpriority="high"
				decoding="async"
			>
			<span class="site-header__name"><?php echo esc_html( $clinic_name ); ?></span>
		</a>

		<button
			type="button"
			class="nav-toggle"
			aria-expanded="false"
			aria-controls="primary-navigation"
		>
			<span class="nav-toggle__box" aria-hidden="true">
				<span class="nav-toggle__bar"></span>
				<span class="nav-toggle__bar"></span>
				<span class="nav-toggle__bar"></span>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'brittos-dentistry' ); ?></span>
		</button>

		<nav id="primary-navigation" class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'brittos-dentistry' ); ?>">
			<?php brittos_nav_menu( 'primary' ); ?>

			<div class="primary-nav__actions">
				<?php if ( $phone ) : ?>
					<a class="button button--ghost" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
						<?php echo esc_html( $phone ); ?>
					</a>
				<?php endif; ?>
				<a class="button button--primary" href="#appointment-form">
					<?php esc_html_e( 'Book an appointment', 'brittos-dentistry' ); ?>
				</a>
			</div>
		</nav>

	</div>
</header>
