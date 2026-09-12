<?php
/**
 * "Why choose this clinic" — factual, configurable trust indicators.
 * Fully configurable from Settings > Clinic Info.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = brittos_clinic_field( 'why_eyebrow', __( 'The Britto Difference', 'brittos-dentistry' ) );
$heading = brittos_clinic_field( 'why_heading', __( 'Dentistry designed around your comfort and trust', 'brittos-dentistry' ) );
$lede    = brittos_clinic_field( 'why_lede', __( 'We believe modern dental care should be calm, clinically rigorous, and completely respectful of your time.', 'brittos-dentistry' ) );

$points = array(
	array(
		'num'   => '01',
		'title' => brittos_clinic_field( 'why_f1_title', __( 'One dentist, every visit', 'brittos-dentistry' ) ),
		'text'  => brittos_clinic_field( 'why_f1_text', __( 'You see the same dentist each time — no unfamiliar faces, no re-explaining your medical history.', 'brittos-dentistry' ) ),
		'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
	),
	array(
		'num'   => '02',
		'title' => brittos_clinic_field( 'why_f2_title', __( 'Unhurried appointments', 'brittos-dentistry' ) ),
		'text'  => brittos_clinic_field( 'why_f2_text', __( 'Visits are intentionally scheduled with ample time to discuss questions and comfort, not just perform procedures.', 'brittos-dentistry' ) ),
		'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
	),
	array(
		'num'   => '03',
		'title' => brittos_clinic_field( 'why_f3_title', __( 'Transparent treatment plans', 'brittos-dentistry' ) ),
		'text'  => brittos_clinic_field( 'why_f3_text', __( 'Costs, timelines, and alternatives are shared upfront in plain language before any clinical work begins.', 'brittos-dentistry' ) ),
		'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
	),
	array(
		'num'   => '04',
		'title' => brittos_clinic_field( 'why_f4_title', __( 'Calm, modern environment', 'brittos-dentistry' ) ),
		'text'  => brittos_clinic_field( 'why_f4_text', __( 'An independent, light-filled space designed for relaxation, dignity, and clinical excellence.', 'brittos-dentistry' ) ),
		'icon'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
	),
);
?>
<section class="why-choose-us" aria-labelledby="why-choose-us-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => $eyebrow,
			'heading' => $heading,
			'lede'    => $lede,
			'align'   => 'center',
			'id'      => 'why-choose-us-heading',
		) ); ?>

		<ul class="why-choose-us__grid">
			<?php foreach ( $points as $point ) : ?>
				<li class="why-choose-us__item" data-reveal data-reveal-group="why-choose-us">
					<div class="why-choose-us__header">
						<div class="why-choose-us__icon" aria-hidden="true">
							<?php echo $point['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<span class="why-choose-us__num" aria-hidden="true"><?php echo esc_html( $point['num'] ); ?></span>
					</div>
					<h3 class="why-choose-us__title"><?php echo esc_html( $point['title'] ); ?></h3>
					<p class="why-choose-us__text"><?php echo esc_html( $point['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
