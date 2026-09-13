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
$clinic_settings = function_exists( 'brittos_core_get_clinic_field' ) ? brittos_core_get_clinic_field() : array();
$clinic_settings = is_array( $clinic_settings ) ? $clinic_settings : array();
$archive_eyebrow = array_key_exists( 'treatments_archive_eyebrow', $clinic_settings ) ? $clinic_settings['treatments_archive_eyebrow'] : __( 'Explore by category', 'brittos-dentistry' );
$archive_heading = array_key_exists( 'treatments_archive_heading', $clinic_settings ) ? $clinic_settings['treatments_archive_heading'] : __( 'Browse by Category', 'brittos-dentistry' );
$archive_other_label = array_key_exists( 'treatments_archive_other_label', $clinic_settings ) ? $clinic_settings['treatments_archive_other_label'] : __( 'Other Treatments', 'brittos-dentistry' );
$archive_empty_text = array_key_exists( 'treatments_archive_empty_text', $clinic_settings ) ? $clinic_settings['treatments_archive_empty_text'] : __( 'Treatment listings are being added — please check back soon.', 'brittos-dentistry' );
?>

<main class="treatments-archive" id="main-content">
	<div class="container page-content treatments-archive__content">

	<?php get_template_part( 'template-parts/components/section-heading', null, array(
		'eyebrow' => $archive_eyebrow,
		'heading' => $archive_heading,
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
			<details class="treatment-category-group">
				<summary class="treatment-category-group__header" data-reveal>
					<span id="category-<?php echo esc_attr( $category->term_id ); ?>-heading" class="treatment-category-group__heading" role="heading" aria-level="2">
						<?php echo esc_html( $category->name ); ?>
					</span>
					<svg class="treatment-category-group__toggle" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</summary>

				<div class="treatment-category-group__content">
					<div class="treatment-category-group__content-inner">
						<div class="treatments__index" role="list">
							<?php
							while ( $category_query->have_posts() ) :
								$category_query->the_post();
								get_template_part( 'template-parts/components/treatment-card', null, array( 'variant' => 'index' ) );
							endwhile;
							wp_reset_postdata();
							?>
						</div>

						<?php if ( $category->description ) : ?>
							<p class="treatment-category-group__description" data-reveal><?php echo esc_html( $category->description ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</details>
		<?php endforeach; ?>

		<?php if ( $uncategorised->have_posts() ) : ?>
			<details class="treatment-category-group">
				<summary class="treatment-category-group__header" data-reveal aria-label="<?php echo esc_attr( $archive_other_label ? $archive_other_label : __( 'Other Treatments', 'brittos-dentistry' ) ); ?>">
					<span id="category-other-heading" class="treatment-category-group__heading" role="heading" aria-level="2">
					<?php echo esc_html( $archive_other_label ); ?>
					</span>
					<svg class="treatment-category-group__toggle" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
						<path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</summary>
				<div class="treatment-category-group__content">
					<div class="treatment-category-group__content-inner">
						<div class="treatments__index" role="list">
							<?php
							while ( $uncategorised->have_posts() ) :
								$uncategorised->the_post();
								get_template_part( 'template-parts/components/treatment-card', null, array( 'variant' => 'index' ) );
							endwhile;
							wp_reset_postdata();
							?>
						</div>
					</div>
				</div>
			</details>
		<?php endif; ?>

	<?php else : ?>
		<?php if ( $archive_empty_text ) : ?>
			<p class="empty-state"><?php echo esc_html( $archive_empty_text ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
