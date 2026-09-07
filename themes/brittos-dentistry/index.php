<?php
/**
 * Fallback template — required by WordPress, used only when no more
 * specific template file matches.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="container page-content">

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
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<p class="empty-state"><?php esc_html_e( 'Nothing found.', 'brittos-dentistry' ); ?></p>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
