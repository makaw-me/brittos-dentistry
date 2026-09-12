<?php
/**
 * Related treatments, matched by shared treatment_category (falls back
 * to other published treatments if none share a category). Reuses the
 * same treatment-card component as the homepage/archive grids.
 *
 * Expects $treatment_id in scope.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'brittos_core_get_related_treatments' ) ) {
	return;
}

if ( empty( $treatment_id ) ) {
	return;
}

$related = brittos_core_get_related_treatments( $treatment_id, 3 );

if ( ! $related->have_posts() ) {
	return;
}
?>
<section class="related-treatments" aria-labelledby="related-treatments-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Continue exploring', 'brittos-dentistry' ),
			'heading' => __( 'You might also be interested in', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'related-treatments-heading',
		) ); ?>

		<div class="treatments__grid">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				get_template_part( 'template-parts/components/treatment-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

	</div>
</section>
