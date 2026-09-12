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
<article <?php post_class( 'treatment-card' ); ?> data-reveal data-reveal-group="treatments">
	<a class="treatment-card__media-link" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'brittos-card', array(
				'class'    => 'treatment-card__image',
				'loading'  => 'lazy',
				'decoding' => 'async',
				'alt'      => '',
			) ); ?>
		<?php else : ?>
			<span class="treatment-card__image treatment-card__image--placeholder" aria-hidden="true">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="treatment-card__icon-placeholder"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
			</span>
		<?php endif; ?>
		<span class="treatment-card__badge-overlay"><?php esc_html_e( 'Explore', 'brittos-dentistry' ); ?></span>
	</a>

	<div class="treatment-card__body">
		<h3 class="treatment-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $short_desc ) : ?>
			<p class="treatment-card__excerpt"><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
		<?php endif; ?>

		<a class="treatment-card__link" href="<?php the_permalink(); ?>">
			<span class="treatment-card__link-text"><?php esc_html_e( 'View treatment details', 'brittos-dentistry' ); ?></span>
			<span class="treatment-card__link-icon" aria-hidden="true">
				<svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
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
