<?php
/**
 * Testimonial card. Expects global $post set to a `testimonial` post.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$patient_name = function_exists( 'brittos_core_get_testimonial_field' )
	? brittos_core_get_testimonial_field( get_the_ID(), 'patient_name' )
	: '';
if ( '' === $patient_name ) {
	$patient_name = get_the_title();
}
?>
<figure <?php post_class( 'testimonial-card' ); ?> data-reveal data-reveal-group="testimonials">
	<blockquote class="testimonial-card__quote">
		<?php the_content(); ?>
	</blockquote>
	<figcaption class="testimonial-card__author">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'brittos-avatar', array(
				'class'    => 'testimonial-card__avatar',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => '',
			) ); ?>
		<?php endif; ?>
		<span class="testimonial-card__name"><?php echo esc_html( $patient_name ); ?></span>
	</figcaption>
</figure>
