<?php
/**
 * Sticky mobile call-to-action bar (call + book). Hidden on larger
 * viewports via CSS; purely additive, never blocks content.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone = brittos_clinic_field( 'phone' );
if ( ! $phone ) {
	return;
}
?>
<div class="mobile-cta" role="region" aria-label="<?php esc_attr_e( 'Quick actions', 'brittos-dentistry' ); ?>">
	<a class="mobile-cta__link" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
		<?php esc_html_e( 'Call', 'brittos-dentistry' ); ?>
	</a>
	<a class="mobile-cta__link mobile-cta__link--primary" href="#appointment-form">
		<?php esc_html_e( 'Book appointment', 'brittos-dentistry' ); ?>
	</a>
</div>
