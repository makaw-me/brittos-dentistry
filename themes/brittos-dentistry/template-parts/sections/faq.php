<?php
/**
 * FAQ section using the `faq` CPT, rendered as accessible <details> items.
 * Also emits FAQPage schema via the core plugin when FAQs exist.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faqs = post_type_exists( 'faq' ) ? new WP_Query( array(
	'post_type'      => 'faq',
	'posts_per_page' => 8,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'meta_query'     => array(
		'relation' => 'OR',
		array(
			'key'     => 'brittos_faq_show_on_home',
			'value'   => '1',
			'compare' => '=',
		),
		array(
			'key'     => 'brittos_faq_show_on_home',
			'compare' => 'NOT EXISTS',
		),
	),
) ) : null;

if ( ! $faqs || ! $faqs->have_posts() ) {
	return;
}
?>
<section class="faq" aria-labelledby="faq-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Questions', 'brittos-dentistry' ),
			'heading' => __( 'Frequently asked questions', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'faq-heading',
		) ); ?>

		<div class="faq__list">
			<?php
			while ( $faqs->have_posts() ) :
				$faqs->the_post();
				get_template_part( 'template-parts/components/faq-item' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

	</div>
</section>
