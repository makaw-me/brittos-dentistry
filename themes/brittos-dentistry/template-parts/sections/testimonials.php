<?php
/**
 * Testimonials section, pulled from the `testimonial` CPT.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$google_reviews = function_exists( 'brittos_core_get_google_reviews' ) ? brittos_core_get_google_reviews() : null;

$testimonials = post_type_exists( 'testimonial' ) ? new WP_Query( array(
	'post_type'      => 'testimonial',
	'posts_per_page' => 3,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
) ) : null;

if ( ( ! $google_reviews || empty( $google_reviews['reviews'] ) ) && ( ! $testimonials || ! $testimonials->have_posts() ) ) {
	return;
}

$show_google_reviews = $google_reviews && ! empty( $google_reviews['reviews'] );
$review_eyebrow = brittos_clinic_field( 'google_reviews_eyebrow', __( 'Google reviews', 'brittos-dentistry' ) );
$review_heading = brittos_clinic_field( 'google_reviews_heading', __( 'What patients say', 'brittos-dentistry' ) );
$rating_label = brittos_clinic_field( 'google_reviews_rating_label', __( 'Rated %s out of 5 stars', 'brittos-dentistry' ) );
$summary_label = brittos_clinic_field( 'google_reviews_summary_label', __( 'Google rating summary', 'brittos-dentistry' ) );
$carousel_label = brittos_clinic_field( 'google_reviews_carousel_label', __( 'Google patient reviews', 'brittos-dentistry' ) );
$count_label = brittos_clinic_field( 'google_reviews_count_label', __( 'Google reviews', 'brittos-dentistry' ) );
$source_label = brittos_clinic_field( 'google_reviews_source_label', __( 'Read all reviews on Google', 'brittos-dentistry' ) );
?>
<section class="testimonials" aria-labelledby="testimonials-heading">

	<?php get_template_part( 'template-parts/components/section-heading', null, array(
		'eyebrow' => $show_google_reviews ? $review_eyebrow : __( 'In their words', 'brittos-dentistry' ),
		'heading' => $show_google_reviews ? $review_heading : __( 'What patients say', 'brittos-dentistry' ),
		'align'   => 'center',
		'id'      => 'testimonials-heading',
	) ); ?>

	<?php if ( $show_google_reviews ) : ?>
		<div class="testimonials__summary" aria-label="<?php echo esc_attr( $summary_label ); ?>">
			<strong><?php echo esc_html( number_format_i18n( $google_reviews['rating'], 1 ) ); ?>/5</strong>
			<span class="testimonials__stars" aria-label="<?php echo esc_attr( sprintf( $rating_label, number_format_i18n( $google_reviews['rating'], 1 ) ) ); ?>" role="img">★★★★★</span>
			<span><?php echo esc_html( number_format_i18n( $google_reviews['rating_count'] ) ); ?> <?php echo esc_html( $count_label ); ?></span>
		</div>

		<div class="testimonials__carousel" role="region" aria-label="<?php echo esc_attr( $carousel_label ); ?>">
			<div class="testimonials__track">
				<?php foreach ( array( false, true ) as $duplicate ) : ?>
					<div class="testimonials__group"<?php echo $duplicate ? ' aria-hidden="true" inert' : ''; ?>>
						<?php foreach ( $google_reviews['reviews'] as $review ) : ?>
							<figure class="testimonial-card testimonial-card--google">
								<?php if ( ! empty( $review['rating'] ) ) : ?>
									<div class="testimonial-card__rating" aria-label="<?php echo esc_attr( sprintf( $rating_label, number_format_i18n( $review['rating'], 1 ) ) ); ?>" role="img">
										<span aria-hidden="true">★★★★★</span>
									</div>
								<?php endif; ?>
								<blockquote class="testimonial-card__quote"><?php echo esc_html( $review['text'] ); ?></blockquote>
								<figcaption class="testimonial-card__author">
									<?php if ( ! empty( $review['author_url'] ) ) : ?><a href="<?php echo esc_url( $review['author_url'] ); ?>" rel="noopener noreferrer" target="_blank"><?php echo esc_html( $review['author'] ); ?></a><?php else : ?><span><?php echo esc_html( $review['author'] ); ?></span><?php endif; ?>
								</figcaption>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( ! empty( $google_reviews['maps_url'] ) ) : ?>
			<p class="testimonials__source">
				<a href="<?php echo esc_url( $google_reviews['maps_url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $source_label ); ?>
				</a>
			</p>
		<?php endif; ?>
	<?php else : ?>
		<div class="testimonials__grid">
			<?php
			while ( $testimonials->have_posts() ) :
				$testimonials->the_post();
				get_template_part( 'template-parts/components/testimonial-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	<?php endif; ?>

</section>
