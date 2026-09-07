<?php
/**
 * Testimonials section, pulled from the `testimonial` CPT.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$testimonials = post_type_exists( 'testimonial' ) ? new WP_Query( array(
	'post_type'      => 'testimonial',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
) ) : null;

if ( ! $testimonials || ! $testimonials->have_posts() ) {
	return;
}
?>
<section class="testimonials" aria-labelledby="testimonials-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'In their words', 'brittos-dentistry' ),
			'heading' => __( 'What patients say', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'testimonials-heading',
		) ); ?>

		<div class="testimonials__grid">
			<?php
			while ( $testimonials->have_posts() ) :
				$testimonials->the_post();
				get_template_part( 'template-parts/components/testimonial-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

	</div>
</section>
