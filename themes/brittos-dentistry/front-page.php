<?php
/**
 * Homepage template: hero through final CTA, each section a template-part
 * so it can be reordered/removed independently.
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

?>
<section class="trust-strip" aria-label="<?php esc_attr_e( 'What to expect', 'brittos-dentistry' ); ?>">
	<div class="container trust-strip__inner">
		<span><?php esc_html_e( 'Thoughtful care', 'brittos-dentistry' ); ?></span>
		<span><?php esc_html_e( 'Modern dentistry', 'brittos-dentistry' ); ?></span>
		<span><?php esc_html_e( 'Clear explanations', 'brittos-dentistry' ); ?></span>
		<span><?php esc_html_e( 'Comfortable visits', 'brittos-dentistry' ); ?></span>
	</div>
</section>

<?php

get_template_part( 'template-parts/sections/about-doctor' );
get_template_part( 'template-parts/sections/treatments' );
get_template_part( 'template-parts/sections/why-choose-us' );
get_template_part( 'template-parts/sections/testimonials' );
get_template_part( 'template-parts/sections/gallery' );
get_template_part( 'template-parts/sections/faq' );
get_template_part( 'template-parts/sections/final-cta' );

get_footer();
