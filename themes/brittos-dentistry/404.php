<?php
/**
 * 404 template.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="container page-content not-found">

	<?php get_template_part( 'template-parts/global/breadcrumbs' ); ?>

	<h1 class="not-found__title"><?php esc_html_e( 'Page not found', 'brittos-dentistry' ); ?></h1>
	<p class="not-found__text">
		<?php esc_html_e( 'The page you were looking for may have moved or no longer exists.', 'brittos-dentistry' ); ?>
	</p>

	<p>
		<a class="button button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Back to homepage', 'brittos-dentistry' ); ?>
		</a>
	</p>

	<?php if ( post_type_exists( 'treatment' ) ) : ?>
		<?php get_template_part( 'template-parts/sections/treatments' ); ?>
	<?php endif; ?>

</div>

<?php get_footer(); ?>
