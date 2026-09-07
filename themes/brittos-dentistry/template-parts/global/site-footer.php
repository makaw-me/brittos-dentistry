<?php
/**
 * Site footer: clinic info, hours, footer nav, credit.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic_name = brittos_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );
$dentist     = brittos_clinic_field( 'dentist_name' );
$phone       = brittos_clinic_field( 'phone' );
$email       = brittos_clinic_field( 'email' );
$address     = brittos_clinic_field( 'address' );
$city        = brittos_clinic_field( 'city' );
$hours       = brittos_clinic_field( 'opening_hours' );
?>
<footer class="site-footer" role="contentinfo">
	<div class="container site-footer__inner">

		<div class="site-footer__intro">
			<p class="site-footer__brand"><?php echo esc_html( $clinic_name ); ?></p>
			<p class="site-footer__tagline"><?php esc_html_e( 'Modern dentistry, delivered with time, clarity and care.', 'brittos-dentistry' ); ?></p>
			<?php if ( $dentist ) : ?>
				<p><?php echo esc_html( $dentist ); ?></p>
			<?php endif; ?>
			<?php if ( $address || $city ) : ?>
				<address class="site-footer__address">
					<?php echo esc_html( trim( $address . ( $address && $city ? ', ' : '' ) . $city ) ); ?>
				</address>
			<?php endif; ?>
		</div>

		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Contact', 'brittos-dentistry' ); ?></h2>
			<ul class="site-footer__list">
				<?php if ( $phone ) : ?>
					<li><a href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<li><a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>

		<?php if ( $hours ) : ?>
			<div class="site-footer__col">
				<h2 class="site-footer__heading"><?php esc_html_e( 'Hours', 'brittos-dentistry' ); ?></h2>
				<p class="site-footer__hours"><?php echo wp_kses_post( nl2br( $hours ) ); ?></p>
			</div>
		<?php endif; ?>

		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Explore', 'brittos-dentistry' ); ?></h2>
			<?php brittos_nav_menu( 'footer', array( 'items_wrap' => '<ul class="site-footer__list">%3$s</ul>' ) ); ?>
		</div>

	</div>

	<div class="site-footer__bottom">
		<div class="container">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $clinic_name ); ?>.
				<?php esc_html_e( 'All rights reserved.', 'brittos-dentistry' ); ?>
			</p>
		</div>
	</div>
</footer>
