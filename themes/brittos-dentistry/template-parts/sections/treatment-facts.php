<?php
/**
 * Quick-facts strip for a single treatment: visits required, session
 * duration, recovery time, or whatever else the clinic has actually
 * entered for this treatment (see Treatment Details > Quick Facts in
 * wp-admin). Renders nothing if the treatment has no facts set.
 *
 * Presented as an elevated card strip that overlaps into view right
 * under the breadcrumb bar, so it reads as a distinct, discoverable
 * surface rather than blending into the page background.
 *
 * Expects $treatment_id in scope.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $treatment_id ) ) {
	return;
}

$facts = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'facts' )
	: array();

if ( empty( $facts ) ) {
	return;
}
?>
<section class="treatment-facts" aria-label="<?php esc_attr_e( 'Quick facts', 'brittos-dentistry' ); ?>">
	<div class="container">
		<div class="treatment-facts__card">
			<ul class="treatment-facts__list">
				<?php foreach ( $facts as $fact ) : ?>
					<li class="treatment-facts__item" data-reveal data-reveal-group="treatment-facts">
						<span class="treatment-facts__icon" aria-hidden="true">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
						</span>
						<span class="treatment-facts__text">
							<span class="treatment-facts__value"><?php echo esc_html( $fact['value'] ); ?></span>
							<span class="treatment-facts__label"><?php echo esc_html( $fact['label'] ); ?></span>
						</span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
