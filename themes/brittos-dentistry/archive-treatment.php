<?php
/**
 * Treatment archive: grid of all published treatments.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="container page-content">

	<?php get_template_part( 'template-parts/global/breadcrumbs' ); ?>

	<?php get_template_part( 'template-parts/components/section-heading', null, array(
		'eyebrow' => __( 'Treatments', 'brittos-dentistry' ),
		'heading' => post_type_archive_title( '', false ),
		'align'   => 'left',
	) ); ?>

	<?php if ( have_posts() ) : ?>
		<div class="treatments__grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/components/treatment-card' );
			endwhile;
			?>
		</div>

		<div class="archive-pagination">
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<p class="empty-state">
			<?php esc_html_e( 'Treatment listings are being added — please check back soon.', 'brittos-dentistry' ); ?>
		</p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
