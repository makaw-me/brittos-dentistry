<?php
/**
 * Generic page template.
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

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'single-page' ); ?>>
			<header class="single-page__header">
				<h1 class="single-page__title"><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="single-page__thumbnail">
					<?php the_post_thumbnail( 'brittos-hero', array(
						'loading'  => 'lazy',
						'decoding' => 'async',
					) ); ?>
				</div>
			<?php endif; ?>

			<div class="single-page__content">
				<?php the_content(); ?>
			</div>

			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>

</div>

<?php get_footer(); ?>
