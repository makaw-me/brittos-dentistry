<?php
/**
 * Treatment card component. Expects global $post set to a `treatment` post
 * (i.e. call within a loop, or use setup_postdata beforehand).
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$treatment_id  = get_the_ID();
$short_desc    = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'short_description' )
	: '';
if ( '' === $short_desc ) {
	$short_desc = get_the_excerpt();
}
?>
<article <?php post_class( 'treatment-card' ); ?>>
	<a class="treatment-card__media-link" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'brittos-card', array(
				'class'    => 'treatment-card__image',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => '',
			) ); ?>
		<?php else : ?>
			<span class="treatment-card__image treatment-card__image--placeholder" aria-hidden="true"></span>
		<?php endif; ?>
	</a>

	<div class="treatment-card__body">
		<h3 class="treatment-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $short_desc ) : ?>
			<p class="treatment-card__excerpt"><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
		<?php endif; ?>

		<a class="treatment-card__link" href="<?php the_permalink(); ?>">
			<span aria-hidden="true"><?php esc_html_e( 'Learn more', 'brittos-dentistry' ); ?> &rarr;</span>
			<span class="screen-reader-text">
				<?php
				printf(
					/* translators: %s: treatment title */
					esc_html__( 'Learn more about %s', 'brittos-dentistry' ),
					esc_html( get_the_title() )
				);
				?>
			</span>
		</a>
	</div>
</article>
