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
$show_tagline = brittos_clinic_setting_enabled( 'footer_show_tagline' );
$show_dentist = brittos_clinic_setting_enabled( 'footer_show_dentist' );
$show_address = brittos_clinic_setting_enabled( 'footer_show_address' );
$show_contact = brittos_clinic_setting_enabled( 'footer_show_contact' );
$show_hours   = brittos_clinic_setting_enabled( 'footer_show_hours' );
$show_nav     = brittos_clinic_setting_enabled( 'footer_show_navigation' );
$show_credit  = brittos_clinic_setting_enabled( 'footer_show_credit' );
$footer_tagline = brittos_clinic_field( 'footer_tagline', __( 'Quiet confidence. Modern dentistry. Human care.', 'brittos-dentistry' ) );
$dentist_label  = brittos_clinic_field( 'footer_dentist_label', __( 'Lead Clinician', 'brittos-dentistry' ) );
$contact_heading = brittos_clinic_field( 'footer_contact_heading', __( 'Direct Contact', 'brittos-dentistry' ) );
$hours_heading = brittos_clinic_field( 'footer_hours_heading', __( 'Clinic Hours', 'brittos-dentistry' ) );
$navigation_heading = brittos_clinic_field( 'footer_navigation_heading', __( 'Navigation', 'brittos-dentistry' ) );
$copyright_text = brittos_clinic_field( 'footer_copyright_text', __( 'All rights reserved.', 'brittos-dentistry' ) );
$credit_text = brittos_clinic_field( 'footer_credit_text', __( 'Clinical precision & human warmth.', 'brittos-dentistry' ) );
$credit_link_enabled = brittos_clinic_setting_enabled( 'footer_credit_link_enabled' );
$credit_prefix = brittos_clinic_field( 'footer_credit_prefix', __( 'Powered by', 'brittos-dentistry' ) );
$credit_name = brittos_clinic_field( 'footer_credit_name', 'MAKAW' );
$credit_url = brittos_clinic_field( 'footer_credit_url', 'https://makaw.me' );
?>
<footer class="site-footer" role="contentinfo">
	<div class="site-footer__ambient" aria-hidden="true"></div>
	<div class="container site-footer__inner">

		<div class="site-footer__intro">
			<div class="site-footer__brand-block">
				<p class="site-footer__brand"><?php echo esc_html( $clinic_name ); ?></p>
				<?php if ( $show_tagline ) : ?>
					<p class="site-footer__tagline"><?php echo esc_html( $footer_tagline ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $show_dentist && $dentist ) : ?>
				<div class="site-footer__dentist">
					<span class="site-footer__dentist-badge"><?php echo esc_html( $dentist_label ); ?></span>
					<p class="site-footer__dentist-name"><?php echo esc_html( $dentist ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $show_address && ( $address || $city ) ) : ?>
				<address class="site-footer__address">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
					<span><?php echo esc_html( trim( $address . ( $address && $city ? ', ' : '' ) . $city ) ); ?></span>
				</address>
			<?php endif; ?>
		</div>

		<?php if ( $show_contact && ( $phone || $email ) ) : ?>
		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php echo esc_html( $contact_heading ); ?></h2>
			<ul class="site-footer__list">
				<?php if ( $phone ) : ?>
					<li>
						<a class="site-footer__link" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
							<span><?php echo esc_html( $phone ); ?></span>
						</a>
					</li>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<li>
						<a class="site-footer__link" href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
							<span><?php echo esc_html( antispambot( $email ) ); ?></span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif; ?>

		<?php if ( $show_hours && $hours ) : ?>
			<div class="site-footer__col">
				<h2 class="site-footer__heading"><?php echo esc_html( $hours_heading ); ?></h2>
				<div class="site-footer__hours">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
					<div><?php echo wp_kses_post( nl2br( $hours ) ); ?></div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $show_nav ) : ?>
		<div class="site-footer__col">
			<h2 class="site-footer__heading"><?php echo esc_html( $navigation_heading ); ?></h2>
			<?php brittos_nav_menu( 'footer', array( 'items_wrap' => '<ul class="site-footer__list">%3$s</ul>' ) ); ?>
		</div>
		<?php endif; ?>

	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $clinic_name ); ?>.
				<?php echo esc_html( $copyright_text ); ?>
			</p>
			<?php if ( $show_credit ) : ?>
				<p class="site-footer__credit">
					<?php if ( $credit_link_enabled && $credit_name && $credit_url ) : ?>
						<span><?php echo esc_html( $credit_prefix ); ?> </span>
						<a href="<?php echo esc_url( $credit_url ); ?>" target="_blank" rel="noopener noreferrer nofollow">
							<?php echo esc_html( $credit_name ); ?>
							<span class="screen-reader-text"> (<?php esc_html_e( 'opens in a new tab', 'brittos-dentistry' ); ?>)</span>
						</a>
					<?php else : ?>
						<?php echo esc_html( $credit_text ); ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>
</footer>
