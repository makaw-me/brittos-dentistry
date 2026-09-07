<?php
/**
 * Generic archive template (blog posts, categories, tags).
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

	<header class="archive-header">
		<h1 class="archive-header__title"><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<div class="archive-header__description">', '</div>' ); ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="archive-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'archive-card' ); ?>>
					<a class="archive-card__link" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'brittos-card', array(
								'class'    => 'archive-card__image',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'alt'      => '',
							) ); ?>
						<?php endif; ?>
						<h2 class="archive-card__title"><?php the_title(); ?></h2>
					</a>
					<p class="archive-card__excerpt"><?php the_excerpt(); ?></p>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="archive-pagination">
			<?php
			the_posts_pagination( array(
				'prev_text' => esc_html__( 'Previous', 'brittos-dentistry' ),
				'next_text' => esc_html__( 'Next', 'brittos-dentistry' ),
			) );
			?>
		</div>
	<?php else : ?>
		<p class="empty-state"><?php esc_html_e( 'Nothing has been published here yet.', 'brittos-dentistry' ); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
