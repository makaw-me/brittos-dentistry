<?php
/**
 * Single Treatment template.
 *
 * Header
 * → Hero (category badge, title, short description, optional image, CTA)
 * → Quick Facts strip (visits/duration/recovery/etc., only what's set)
 * → Gutenberg content from the editor
 * → Treatment-specific FAQs (owned by the FAQ side of the relationship)
 * → Before & After gallery (only if pairs are set)
 * → Related Treatments (shared category, falls back to recent)
 * → Final appointment CTA
 * Footer
 *
 * Structured fields are read via brittos_core_get_treatment_field() from
 * the core plugin and degrade gracefully when unset — nothing here
 * invents facts, benefits, or before/after content that wasn't entered.
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
	$has_field    = function_exists( 'brittos_core_get_treatment_field' );

	$short_desc = $has_field ? brittos_core_get_treatment_field( $treatment_id, 'short_description' ) : '';
	if ( ! $short_desc ) {
		$short_desc = get_the_excerpt();
	}

	$benefits = $has_field ? brittos_core_get_treatment_field( $treatment_id, 'benefits' ) : array();
	$process  = $has_field ? brittos_core_get_treatment_field( $treatment_id, 'process' ) : array();
	$cta_text = $has_field ? brittos_core_get_treatment_field( $treatment_id, 'cta_text' ) : '';
	$cta_url  = $has_field ? brittos_core_get_treatment_field( $treatment_id, 'cta_url' ) : '';
	if ( ! $cta_url ) {
		$cta_url = function_exists( 'brittos_core_get_booking_url' ) ? brittos_core_get_booking_url() : home_url( '/' );
	}

	$hero_overlay = $has_field ? ( '1' === brittos_core_get_treatment_field( $treatment_id, 'hero_overlay' ) ) : false;
	$hero_overlay = $hero_overlay && has_post_thumbnail();

	$category = get_the_terms( $treatment_id, 'treatment_category' );
	$category = ( $category && ! is_wp_error( $category ) ) ? $category[0] : null;
	?>

	<article <?php post_class( 'treatment-single' ); ?>>

		<header class="treatment-hero<?php echo $hero_overlay ? ' treatment-hero--overlay' : ''; ?>">
			<?php if ( $hero_overlay ) : ?>
				<div class="treatment-hero__bg" aria-hidden="true">
					<?php echo get_the_post_thumbnail( $treatment_id, 'brittos-hero', array( 'class' => 'treatment-hero__bg-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<div class="treatment-hero__bg-overlay"></div>
				</div>
			<?php endif; ?>

			<div class="container treatment-hero__inner">

				<div class="treatment-hero__content" data-reveal>
					<?php if ( $category ) : ?>
						<span class="hero__badge treatment-hero__badge">
							<span class="hero__badge-dot" aria-hidden="true"></span>
							<span class="hero__badge-text"><?php echo esc_html( $category->name ); ?></span>
						</span>
					<?php endif; ?>

					<h1 class="hero__title treatment-hero__title"><?php the_title(); ?></h1>

					<?php if ( $short_desc ) : ?>
						<p class="hero__lede treatment-hero__lede"><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
					<?php endif; ?>

					<div class="hero__actions">
						<?php brittos_button( array(
							'text'  => $cta_text ? $cta_text : __( 'Book an appointment', 'brittos-dentistry' ),
							'url'   => $cta_url,
							'style' => 'primary',
							'icon'  => 'arrow',
						) ); ?>
					</div>
				</div>

				<?php if ( has_post_thumbnail() && ! $hero_overlay ) : ?>
					<div class="treatment-hero__media" data-reveal>
						<?php the_post_thumbnail( 'brittos-hero', array(
							'class'    => 'treatment-hero__image',
							'loading'  => 'lazy',
							'decoding' => 'async',
						) ); ?>
					</div>
				<?php endif; ?>

			</div>
		</header>

		<?php get_template_part( 'template-parts/sections/treatment-facts', null, array( 'treatment_id' => $treatment_id ) ); ?>

		<div class="container treatment-single__body">

			<div class="treatment-single__content">
				<?php the_content(); ?>
			</div>

			<?php if ( ! empty( $benefits ) ) : ?>
				<section aria-labelledby="treatment-benefits-heading">
					<h2 id="treatment-benefits-heading"><?php esc_html_e( 'Benefits', 'brittos-dentistry' ); ?></h2>
					<ul class="treatment-single__benefits">
						<?php foreach ( $benefits as $benefit ) : ?>
							<li data-reveal data-reveal-group="benefits">
								<span class="treatment-single__benefit-icon" aria-hidden="true">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
								</span>
								<span><?php echo esc_html( $benefit ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php if ( ! empty( $process ) ) : ?>
				<section aria-labelledby="treatment-process-heading">
					<h2 id="treatment-process-heading"><?php esc_html_e( 'Your treatment journey', 'brittos-dentistry' ); ?></h2>
					<ol class="treatment-single__process">
						<?php foreach ( $process as $step ) : ?>
							<li data-reveal data-reveal-group="process">
								<span class="treatment-single__process-content"><?php echo wp_kses_post( $step ); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
			<?php endif; ?>

		</div>

		<?php
		if ( function_exists( 'brittos_core_get_related_faqs_for_treatment' ) ) :
			$treatment_faqs = brittos_core_get_related_faqs_for_treatment( $treatment_id );
			if ( $treatment_faqs->have_posts() ) :
				?>
				<section class="faq treatment-single__faq" aria-labelledby="treatment-faq-heading">
					<div class="container">
						<?php get_template_part( 'template-parts/components/section-heading', null, array(
							'eyebrow' => __( 'Questions about this treatment', 'brittos-dentistry' ),
							'heading' => __( 'Common questions', 'brittos-dentistry' ),
							'align'   => 'center',
							'id'      => 'treatment-faq-heading',
						) ); ?>
						<div class="faq__list">
							<?php
							while ( $treatment_faqs->have_posts() ) :
								$treatment_faqs->the_post();
								get_template_part( 'template-parts/components/faq-item' );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</section>
				<?php
			endif;
		endif;
		?>

		<?php get_template_part( 'template-parts/sections/before-after-gallery', null, array( 'treatment_id' => $treatment_id ) ); ?>

		<?php get_template_part( 'template-parts/sections/related-treatments', null, array( 'treatment_id' => $treatment_id ) ); ?>

		<?php get_template_part( 'template-parts/sections/final-cta' ); ?>

	</article>

<?php endwhile; ?>

<?php get_footer(); ?>
