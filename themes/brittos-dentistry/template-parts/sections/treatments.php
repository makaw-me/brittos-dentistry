<?php
/**
 * Treatments overview grid, pulled from the `treatment` CPT (brittos-core).
 * Renders gracefully with an empty-state message when none exist yet.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$treatments = post_type_exists( 'treatment' ) ? new WP_Query( array(
	'post_type'      => 'treatment',
	'posts_per_page' => 6,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) ) : null;
?>
<section class="treatments" aria-labelledby="treatments-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Treatments', 'brittos-dentistry' ),
			'heading' => __( 'Care built around what you actually need', 'brittos-dentistry' ),
			'lede'    => __( 'A focused range of general and cosmetic treatments — explained clearly, with no upselling.', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'treatments-heading',
		) ); ?>

		<?php if ( $treatments && $treatments->have_posts() ) : ?>
			<div class="treatments__grid">
				<?php
				while ( $treatments->have_posts() ) :
					$treatments->the_post();
					get_template_part( 'template-parts/components/treatment-card' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<?php
			$archive_link = get_post_type_archive_link( 'treatment' );
			if ( $archive_link ) :
				?>
				<p class="treatments__all">
					<a class="button button--secondary" href="<?php echo esc_url( $archive_link ); ?>">
						<?php esc_html_e( 'View all treatments', 'brittos-dentistry' ); ?>
					</a>
				</p>
			<?php endif; ?>
		<?php else : ?>
			<p class="empty-state">
				<?php esc_html_e( 'Treatment listings are being added — please check back soon, or call the clinic to ask about a specific procedure.', 'brittos-dentistry' ); ?>
			</p>
		<?php endif; ?>

	</div>
</section>
