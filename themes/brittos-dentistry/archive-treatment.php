<?php
/**
 * Treatment archive: treatments grouped clearly by treatment_category,
 * each as its own labelled section, so visitors browse by the kind of
 * care they're looking for rather than one flat undifferentiated grid.
 * Categories and treatments are both pulled live from the CPT/taxonomy —
 * nothing here is hard-coded, so it always reflects real content.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

get_template_part( 'template-parts/sections/treatments-archive-hero' ); ?>

<?php
$categories = get_terms( array(
	'taxonomy'   => 'treatment_category',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
) );
if ( is_wp_error( $categories ) ) {
	$categories = array();
}

// Treatments with no category assigned still need somewhere to live.
$categorised_ids = get_posts( array(
	'post_type'      => 'treatment',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'fields'         => 'ids',
	'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		array(
			'taxonomy' => 'treatment_category',
			'operator' => 'EXISTS',
		),
	),
) );

$uncategorised = new WP_Query( array(
	'post_type'      => 'treatment',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'post__not_in'   => $categorised_ids ? $categorised_ids : array( 0 ),
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'no_found_rows'  => true,
) );

$has_any_treatments = ! empty( $categories ) || $uncategorised->have_posts();
?>

<div class="container page-content">

	<?php get_template_part( 'template-parts/components/section-heading', null, array(
		'eyebrow' => __( 'Explore by category', 'brittos-dentistry' ),
		'heading' => __( 'Browse by Category', 'brittos-dentistry' ),
		'align'   => 'left',
	) ); ?>

	<?php if ( $has_any_treatments ) : ?>

		<?php foreach ( $categories as $category ) : ?>
			<?php
			$category_query = new WP_Query( array(
				'post_type'      => 'treatment',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'treatment_category',
						'field'    => 'term_id',
						'terms'    => $category->term_id,
					),
				),
			) );

			if ( ! $category_query->have_posts() ) {
				continue;
			}
			?>
			<section class="treatment-category-group" aria-labelledby="category-<?php echo esc_attr( $category->term_id ); ?>-heading">
				<h2 id="category-<?php echo esc_attr( $category->term_id ); ?>-heading" class="treatment-category-group__heading" data-reveal>
					<?php echo esc_html( $category->name ); ?>
				</h2>
				<?php if ( $category->description ) : ?>
					<p class="treatment-category-group__description"><?php echo esc_html( $category->description ); ?></p>
				<?php endif; ?>

				<div class="treatments__grid">
					<?php
					while ( $category_query->have_posts() ) :
						$category_query->the_post();
						get_template_part( 'template-parts/components/treatment-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endforeach; ?>

		<?php if ( $uncategorised->have_posts() ) : ?>
			<section class="treatment-category-group" aria-labelledby="category-other-heading">
				<h2 id="category-other-heading" class="treatment-category-group__heading">
					<?php esc_html_e( 'Other Treatments', 'brittos-dentistry' ); ?>
				</h2>
				<div class="treatments__grid">
					<?php
					while ( $uncategorised->have_posts() ) :
						$uncategorised->the_post();
						get_template_part( 'template-parts/components/treatment-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>

	<?php else : ?>
		<p class="empty-state">
			<?php esc_html_e( 'Treatment listings are being added — please check back soon.', 'brittos-dentistry' ); ?>
		</p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
