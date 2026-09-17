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
$whatsapp    = brittos_clinic_field( 'whatsapp_number' );
$email       = brittos_clinic_field( 'email' );
$address     = brittos_clinic_field( 'address' );
$city        = brittos_clinic_field( 'city' );
$state       = brittos_clinic_field( 'state' );
$postal_code = brittos_clinic_field( 'postal_code' );
$hours       = brittos_clinic_field( 'opening_hours' );
$state_postal = trim( $state . ' ' . $postal_code );
$full_address = implode( ', ', array_filter( array( $address, $city, $state_postal ) ) );
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

			<?php if ( $show_address && $full_address ) : ?>
				<address class="site-footer__address">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
					<span><?php echo esc_html( $full_address ); ?></span>
				</address>
			<?php endif; ?>
		</div>

		<?php if ( $show_nav ) : ?>
			<div class="site-footer__col">
				<h2 class="site-footer__heading"><?php echo esc_html( $navigation_heading ); ?></h2>
				<?php brittos_nav_menu( 'footer', array( 'items_wrap' => '<ul class="site-footer__list">%3$s</ul>' ) ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_contact && ( $phone || $whatsapp || $email ) ) : ?>
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
					<?php if ( $whatsapp && function_exists( 'brittos_whatsapp_href' ) && brittos_whatsapp_href( $whatsapp ) ) : ?>
						<li>
							<a class="site-footer__link" href="<?php echo esc_url( brittos_whatsapp_href( $whatsapp ) ); ?>">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
								<span><?php echo esc_html( $whatsapp ); ?></span>
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
