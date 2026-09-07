<?php
/**
 * Single Treatment template: hero, description, benefits, process, FAQs, CTA.
 * Structured fields are read via brittos_core_get_treatment_field() from
 * the core plugin and degrade gracefully when unset.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$treatment_id = get_the_ID();
	$get_field    = function_exists( 'brittos_core_get_treatment_field' ) ? 'brittos_core_get_treatment_field' : null;

	$short_desc = $get_field ? $get_field( $treatment_id, 'short_description' ) : '';
	$benefits   = $get_field ? $get_field( $treatment_id, 'benefits' ) : array();
	$process    = $get_field ? $get_field( $treatment_id, 'process' ) : array();
	$faq_ids    = $get_field ? $get_field( $treatment_id, 'faq_ids' ) : array();
	?>

	<article <?php post_class( 'treatment-single' ); ?>>

		<header class="treatment-single__hero">
			<div class="container treatment-single__hero-inner">
				<div class="treatment-single__header-content">
					<?php get_template_part( 'template-parts/global/breadcrumbs' ); ?>
					<h1 class="treatment-single__title"><?php the_title(); ?></h1>
					<?php if ( $short_desc ) : ?>
						<p class="treatment-single__lede"><?php echo esc_html( $short_desc ); ?></p>
					<?php endif; ?>
					<?php brittos_button( array(
						'text'  => __( 'Book an appointment', 'brittos-dentistry' ),
						'url'   => home_url( '/#appointment-form' ),
						'style' => 'primary',
					) ); ?>
				</div>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="treatment-single__media">
						<?php the_post_thumbnail( 'brittos-hero', array(
							'loading'  => 'lazy',
							'decoding' => 'async',
						) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<div class="container treatment-single__body">

			<div class="treatment-single__content">
				<?php the_content(); ?>
			</div>

			<?php if ( ! empty( $benefits ) && is_array( $benefits ) ) : ?>
				<section aria-labelledby="treatment-benefits-heading">
					<h2 id="treatment-benefits-heading"><?php esc_html_e( 'Benefits', 'brittos-dentistry' ); ?></h2>
					<ul class="treatment-single__benefits">
						<?php foreach ( $benefits as $benefit ) : ?>
							<li><?php echo esc_html( $benefit ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( ! empty( $process ) && is_array( $process ) ) : ?>
				<section aria-labelledby="treatment-process-heading">
					<h2 id="treatment-process-heading"><?php esc_html_e( 'What to expect', 'brittos-dentistry' ); ?></h2>
					<ol class="treatment-single__process">
						<?php foreach ( $process as $step ) : ?>
							<li><?php echo wp_kses_post( $step ); ?></li>
						<?php endforeach; ?>
					</ol>
				</section>
			<?php endif; ?>

			<?php if ( ! empty( $faq_ids ) && is_array( $faq_ids ) ) : ?>
				<section aria-labelledby="treatment-faq-heading">
					<h2 id="treatment-faq-heading"><?php esc_html_e( 'Common questions', 'brittos-dentistry' ); ?></h2>
					<div class="faq__list">
						<?php
						$treatment_faqs = new WP_Query( array(
							'post_type'      => 'faq',
							'post__in'       => array_map( 'absint', $faq_ids ),
							'orderby'        => 'post__in',
							'posts_per_page' => count( $faq_ids ),
							'no_found_rows'  => true,
						) );
						while ( $treatment_faqs->have_posts() ) :
							$treatment_faqs->the_post();
							get_template_part( 'template-parts/components/faq-item' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>

		</div>

		<?php get_template_part( 'template-parts/sections/final-cta' ); ?>

	</article>

<?php endwhile; ?>

<?php get_footer(); ?>
