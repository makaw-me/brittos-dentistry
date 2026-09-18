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

$args = wp_parse_args( $args ?? array(), array(
	'variant' => 'default',
) );

$card_classes = 'treatment-card';
if ( 'compact' === $args['variant'] ) {
	$card_classes .= ' treatment-card--compact';
} elseif ( 'index' === $args['variant'] ) {
	$card_classes .= ' treatment-card--index';
}

$treatment_id  = get_the_ID();
$image_id      = function_exists( 'brittos_core_get_treatment_image_id' ) ? brittos_core_get_treatment_image_id( $treatment_id ) : get_post_thumbnail_id( $treatment_id );
$has_single_page = ! function_exists( 'brittos_core_treatment_has_single_page' ) || brittos_core_treatment_has_single_page( $treatment_id );
$short_desc    = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'short_description' )
	: '';
$starting_price = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'starting_price' )
	: '';
$category_label = '';
$categories     = get_the_terms( $treatment_id, 'treatment_category' );
if ( $categories && ! is_wp_error( $categories ) ) {
	$category_label = implode( ', ', wp_list_pluck( $categories, 'name' ) );
}
if ( '' === $short_desc ) {
	$short_desc = get_the_excerpt();
}
?>
<article <?php post_class( $card_classes ); ?> data-reveal data-reveal-group="treatments">
	<?php if ( $has_single_page ) : ?><a class="treatment-card__media-link" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php endif; ?>
		<?php if ( $image_id ) : ?>
			<?php echo wp_get_attachment_image( $image_id, 'brittos-card', false, array(
				'class'   => 'treatment-card__image',
				'loading' => 'lazy',
				'decoding' => 'async',
				'alt'     => '',
				// Cards sit in a 3-col grid (≈380 px), 2-col at tablet (≈480 px),
				// full-width on mobile — give the browser real render widths.
				'sizes'   => '(max-width: 600px) 100vw, (max-width: 1024px) 50vw, 380px',
			) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<span class="treatment-card__image treatment-card__image--placeholder" aria-hidden="true">
				<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="treatment-card__icon-placeholder"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
			</span>
		<?php endif; ?>
		<?php if ( 'default' === $args['variant'] && $category_label ) : ?>
			<span class="treatment-card__badge-overlay"><?php echo esc_html( $category_label ); ?></span>
		<?php endif; ?>
	<?php if ( $has_single_page ) : ?></a><?php endif; ?>

	<div class="treatment-card__body">
		<div class="treatment-card__heading-row">
			<h3 class="treatment-card__title">
				<?php if ( $has_single_page ) : ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php the_title(); ?><?php if ( $has_single_page ) : ?></a><?php endif; ?>
			</h3>

			<?php if ( '' !== (string) $starting_price && is_numeric( $starting_price ) ) : ?>
				<p class="treatment-card__price"><?php printf( esc_html__( 'Starting from INR %s', 'brittos-dentistry' ), esc_html( number_format_i18n( absint( $starting_price ) ) ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $short_desc ) : ?>
			<p class="treatment-card__excerpt"><?php echo esc_html( wp_strip_all_tags( $short_desc ) ); ?></p>
		<?php endif; ?>

		<?php if ( $has_single_page ) : ?><a class="treatment-card__link" href="<?php the_permalink(); ?>">
			<span class="treatment-card__link-text"><?php echo 'index' === $args['variant'] ? esc_html__( 'Read treatment overview', 'brittos-dentistry' ) : esc_html__( 'View treatment details', 'brittos-dentistry' ); ?></span>
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
		</a><?php else : ?><span class="treatment-card__link treatment-card__link--unavailable"><?php esc_html_e( 'Available at the clinic', 'brittos-dentistry' ); ?></span><?php endif; ?>
	</div>
</article>
