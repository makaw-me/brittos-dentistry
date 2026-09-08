<?php
/**
 * Homepage template: hero through final CTA, each section a template-part
 * so it can be reordered/removed independently, plus native support for
 * editorial Gutenberg content entered via the WordPress Block Editor.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/sections/hero' );
	}
	rewind_posts();
} else {
	get_template_part( 'template-parts/sections/hero' );
}

$trust_pt1 = brittos_clinic_field( 'trust_point_1', __( 'Thoughtful, unhurried care', 'brittos-dentistry' ) );
$trust_pt2 = brittos_clinic_field( 'trust_point_2', __( 'Modern clinical precision', 'brittos-dentistry' ) );
$trust_pt3 = brittos_clinic_field( 'trust_point_3', __( 'Upfront transparent pricing', 'brittos-dentistry' ) );
$trust_pt4 = brittos_clinic_field( 'trust_point_4', __( 'Calm, comfortable visits', 'brittos-dentistry' ) );
?>
<section class="trust-strip" aria-label="<?php esc_attr_e( 'What to expect', 'brittos-dentistry' ); ?>">
	<div class="container trust-strip__inner">
		<div class="trust-strip__item">
			<span class="trust-strip__icon" aria-hidden="true">✦</span>
			<span class="trust-strip__text"><?php echo esc_html( $trust_pt1 ); ?></span>
		</div>
		<div class="trust-strip__item">
			<span class="trust-strip__icon" aria-hidden="true">✦</span>
			<span class="trust-strip__text"><?php echo esc_html( $trust_pt2 ); ?></span>
		</div>
		<div class="trust-strip__item">
			<span class="trust-strip__icon" aria-hidden="true">✦</span>
			<span class="trust-strip__text"><?php echo esc_html( $trust_pt3 ); ?></span>
		</div>
		<div class="trust-strip__item">
			<span class="trust-strip__icon" aria-hidden="true">✦</span>
			<span class="trust-strip__text"><?php echo esc_html( $trust_pt4 ); ?></span>
		</div>
	</div>
</section>

<?php
// If editor content was added in WordPress Pages > Home, render it here seamlessly.
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		if ( trim( get_the_content() ) !== '' ) {
			echo '<div class="homepage-editorial-content">';
			the_content();
			echo '</div>';
		}
	}
	rewind_posts();
}

get_template_part( 'template-parts/sections/about-doctor' );
get_template_part( 'template-parts/sections/treatments' );
get_template_part( 'template-parts/sections/why-choose-us' );
get_template_part( 'template-parts/sections/testimonials' );
get_template_part( 'template-parts/sections/gallery' );
get_template_part( 'template-parts/sections/faq' );
get_template_part( 'template-parts/sections/final-cta' );

get_footer();
